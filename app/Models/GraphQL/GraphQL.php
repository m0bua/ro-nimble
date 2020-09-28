<?php

namespace App\Models\GraphQL;

use App\Interfaces\GraphQLInterface;
use GraphQL\Client;
use GraphQL\InlineFragment;
use GraphQL\Query;
use GraphQL\RawObject;

/**
 * Class GraphQL
 * @package App\Library\Services
 */
abstract class GraphQL implements GraphQLInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    private string $serviceName;

    /**
     * @var string
     */
    private string $entityName;

    /**
     * @var Query
     */
    protected Query $query;

    /**
     * @var array
     */
    protected array $arguments = [];

    /**
     * @var array
     */
    protected array $selectionSet = [];

    /**
     * GraphQL constructor.
     */
    public function __construct()
    {
        $this->serviceName = $this->serviceName();
        $this->entityName = $this->entityName();

        $this->query = new Query($this->entityName);
        $this->client = new Client(
            config("graphql.{$this->serviceName}.endpoint_url"),
            config("graphql.{$this->serviceName}.authorization_headers"),
            config("graphql.{$this->serviceName}.http_options")
        );
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param string $entity
     * @return Query
     */
    public function query(string $entity): Query
    {
        return new Query($entity);
    }

    /**
     * @param string $entity
     * @return InlineFragment
     */
    public function inlineFragment(string $entity): InlineFragment
    {
        return new InlineFragment($entity);
    }

    /**
     * @param string $fieldName
     * @param $value
     * @return array|RawObject[]
     */
    public function where(string $fieldName, $value): array
    {
        return ['where' => new RawObject("{{$fieldName}_eq: {$value}}")];
    }

    /**
     * @param string $fieldName
     * @param array $values
     * @return array|RawObject[]
     */
    public function whereIn(string $fieldName, array $values): array
    {
        $valuesStr = '[' . join(',', $values) . ']';

        return ['where' => new RawObject("{{$fieldName}_in: {$valuesStr}}")];
    }

    /**
     * @param int $batchSize
     * @param int $batchId
     * @return array|RawObject[]
     */
    public function batch(int $batchSize, int $batchId): array
    {
        return ['batch' => new RawObject("{batchSize: $batchSize batchID: $batchId}")];
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->client
            ->runQuery($this->query, true)
            ->getResults()['data'][$this->entityName];
    }

    /**
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $this->query->setArguments(
            $this->where('id', $id)
        );

        return $this->get();
    }
}
