<?php
declare(strict_types=1);

namespace App\Models\Elastic;

use App\Helpers\ConvertString;
use App\ValueObjects\Method;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Prophecy\Exception\Doubler\MethodNotFoundException;

/**
 * Class Elastic
 * @package App\Models\Elastic
 */
abstract class Elastic
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Index (db name) associated with the model
     *
     * @var string
     */
    private $index;

    /**
     * Type (table name) associated with the model
     *
     * @var string
     */
    private $type;

    /**
     * Parameters for query
     *
     * @var array
     */
    private $params;

    /**
     * Data for parameters
     *
     * @var array
     */
    private $data;

    /**
     * Elastic constructor.
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
        $this->index = $this->indexName();
        $this->type = $this->typeName();
    }

    /**
     * Реализуется в дочернем классе для определения индекса (базы)
     *
     * @return string
     */
    abstract public function indexName(): string;

    /**
     * Реализуется в дочернем классе для определения типа (таблицы)
     *
     * @return string
     */
    abstract public function typeName(): string;

    /**
     * Реализуется в дочернем классе, возвращает список полей
     *
     * @return string
     */
    abstract public function getFields(): array;

    /**
     * @param string $name
     * @param array $arguments
     */
    public function __call(string $name, array $arguments)
    {
        try {
            $method = new Method($this, $name);
            $property = $method->getProperty();

            switch ($method->getPrefix()) {
                case Method::GET:

                    return $this->{$property->getName()};
                    break;
                case Method::SET:
                    $this->setField($property->getName(), array_shift($arguments));

                    return $this;
                    break;
                default:
                    throw new MethodNotFoundException("Method {$name} not found.", get_class($this), $name);
                    break;
            }
        } catch (\Throwable $t) {
            report($t);
            abort(500, $t->getMessage());
        }
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function search(array $params = [])
    {
        return $this->prepareParams($params)->client->search($this->params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function index(array $params = [])
    {
        return $this->prepareParams($params)->client->index($this->params);
    }

    /**
     * @param array $params
     * @return array|callable
     */
    public function delete(array $params = [])
    {
        return $this->prepareParams($params)->client->delete($this->params);
    }

    /**
     * @param array $params
     * @return $this
     */
    private function prepareParams(array $params = []): self
    {
        $this->params = array_merge([
            'index' => $this->index,
            'type' => $this->type,
        ], $params);

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return $this
     */
    public function load(array $data)
    {
        foreach ($data as $field => $value) {
            $this->setField($field, $value);
        }

        return $this;
    }

    /**
     * @param $fieldName
     * @param $fieldValue
     */
    private function setField(string $fieldName, $fieldValue)
    {
        $this->$fieldName = $fieldValue;
    }


    /**
     * @return array|callable
     */
    public function save()
    {
        foreach ($this->getFields() as $field => $value) {
            $this->params[ConvertString::camelCaseToSnake($field)] = $this->{$field};
        }

        return $this->index([
            'id' => $this->params['id'],
            'body' => $this->params
        ]);
    }
}
