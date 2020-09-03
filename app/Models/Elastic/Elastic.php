<?php
declare(strict_types=1);

namespace App\Models\Elastic;

use App\Helpers\Immutable;
use App\Interfaces\ElasticInterface;
use App\ValueObjects\Method;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use ReflectionClass;
use ReflectionException;

/**
 * Class Elastic
 * @package App\Models\Elastic
 */
abstract class Elastic extends Immutable implements ElasticInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Index associated with the model
     *
     * @var string
     */
    private $index;

    /**
     * Parameters for query
     *
     * @var array
     */
    private $params;

    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * Elastic constructor.
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->reflectionClass = new ReflectionClass(get_class($this));
        $this->client = ClientBuilder::create()
            ->setHosts(config('database.elasticsearch.hosts'))
            ->setBasicAuthentication(
                config('database.elasticsearch.basic_auth.username'),
                config('database.elasticsearch.basic_auth.password')
            )
            ->build();
        $this->index = $this->indexName();
        $this->checkRequired();
    }

    /**
     * Указывает на возможные типы, которые конкретное поле сможет принимать от сторонних сервисов
     *
     * @return array
     */
    public function typeIndication(): array
    {
        return [];
    }

    /**
     * Возвращает список полей индекса текущей модели
     *
     * @param array $fieldNames
     * @return array
     * @throws ReflectionException
     */
    public function getFields(array $fieldNames = []): array
    {
        $fields = [];
        array_map(function ($property) use (&$fields, $fieldNames) {
            if (get_class($this) === $property->class) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                $propertyValue = $property->getValue($this);
                if (!empty($fieldNames)) {
                    if (in_array($propertyName, $fieldNames)) {
                        $fields[$propertyName] = $propertyValue;
                    }
                } else {
                    $fields[$propertyName] = $propertyValue;
                }
            }
        }, $this->reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED));

        return $fields;
    }


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
        $this->checkRequiredIsSet();
        return $this->prepareParams(array_merge(['body' => $this->getFields()], $params))->client->index($this->params);
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
     * Возвращает source-результат поиска
     *
     * @param array $searchResult
     * @return array
     */
    public function getSource(array $searchResult): array
    {
        if (!isset($searchResult['hits'])) {
            return array_column($searchResult, '_source');
        }

        return $this->getSource($searchResult['hits']);
    }

    /**
     * @param string $fieldName
     * @param $fieldValue
     * @throws Exception
     */
    private function setField(string $fieldName, $fieldValue)
    {
        $typeIndication = $this->typeIndication();

        if (array_key_exists($fieldName, $typeIndication)) {
            $ownType = $typeIndication[$fieldName]['own_type'];
            $incomingType = gettype($fieldValue);

            if (in_array($incomingType, $typeIndication[$fieldName]['possible_types'])) {
                if (!settype($fieldValue, $ownType)) {
                    throw new Exception("Can't transform type of field '{$fieldName}'. Type expected: {$ownType}; Type given: {$incomingType}");
                }
            } else {
                throw new Exception("Can't resolve type of field '{$fieldName}'. Type expected: {$ownType}; Type given: {$incomingType}");
            }
        }

        $this->$fieldName = $fieldValue;
    }

    /**
     * @param array $params
     * @return $this
     */
    private function prepareParams(array $params = []): self
    {
        $this->params = array_merge(['index' => $this->index], $params);

        return $this;
    }

    /**
     * Проверяет на наличие обязательных полей
     *
     * @throws Exception
     */
    private function checkRequired()
    {
        $properties = [];
        array_map(function ($property) use (&$properties) {
            if (get_class($this) === $property->class) {
                $properties[] = $property->getName();
            }
        }, $this->reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED));

        $diff = array_diff($this->requiredFields(), $properties);
        if (!empty($diff)) {
            throw new Exception(sprintf("Required fields is missing: %s", join(', ', $diff)));
        }
    }

    /**
     * Проверяет заполнены ли обязательные поля
     */
    private function checkRequiredIsSet()
    {
        array_map(function ($field) {
            if (!isset($this->$field)) {
                throw new Exception(sprintf('Field "%s" can not be null', $field));
            }
        }, $this->requiredFields());
    }
}
