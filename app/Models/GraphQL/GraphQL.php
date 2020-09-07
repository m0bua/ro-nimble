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
     * @param array $selectionSet
     * @return $this
     */
    public function setSelectionSet(array $selectionSet): self
    {
        $this->query->setSelectionSet($selectionSet);

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments): self
    {
        $this->query->setArguments($arguments);

        return $this;
    }

    /**
     * @param $fieldName
     * @param $value
     * @return $this
     */
    public function setArgumentsWhere($fieldName, $value): self
    {
        $this->query->setArguments(['where' => new RawObject("{{$fieldName}: {$value}}")]);

        return $this;
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
        return $this->setArgumentsWhere('id_eq', $id)->get();
    }
}
