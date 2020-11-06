<?php

namespace App\Models\GraphQL;

use App\Interfaces\GraphQLInterface;
use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\InlineFragment;
use GraphQL\Query;
use GraphQL\RawObject;
use GraphQL\Variable;

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
    protected array $vars = [];

    /**
     * @var array
     */
    protected array $varsValues = [];

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
    public function whereEq(string $fieldName, $value): array
    {
        $this->varsValues['where'] = [
            $fieldName . '_eq' => $value
        ];

        return $this->where();
    }

    /**
     * @param string $fieldName
     * @param array $values
     * @return array|RawObject[]
     */
    public function whereIn(string $fieldName, array $values): array
    {
        $this->varsValues['where'] = [
            $fieldName . '_in' => array_values($values)
        ];

        return $this->where();
    }

    /**
     * @return string[]
     */
    public function where(): array
    {
        $this->vars['where'] = new Variable('where', 'Map!');

        return ['where' => '$where'];
    }

    /**
     * @param int $batchSize
     * @param int $batchId
     * @return array|RawObject[]
     */
    public function batch(int $batchSize, int $batchId): array
    {
        $this->varsValues['batch'] = [
            'batchSize' => $batchSize,
            'batchID' => $batchId,
        ];

        $this->vars['batch'] = new Variable('batch', 'Batch');

        return ['batch' => '$batch'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function get(): array
    {
        if ($this->vars) {
            $this->query->setVariables(array_values($this->vars));
        }

        try {
            return $this->client
                ->runQuery($this->query, true, $this->varsValues)
                ->getResults()['data'][$this->entityName];
        } catch (QueryError $exception) {
            throw new \Exception(json_encode($exception->getErrorDetails()));
        }
    }

    /**
     * @param int $id
     * @return array
     */
    public function getById(int $id): array
    {
        $this->query->setArguments(
            $this->whereEq('id', $id)
        );

        return $this->get();
    }
}
