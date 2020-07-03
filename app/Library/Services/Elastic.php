<?php
declare(strict_types=1);

namespace App\Library\Services;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

/**
 * Class Elastic
 * @package App\Library\Services
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
}
