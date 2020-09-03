<?php


namespace App\Models\GraphQL;


use GraphQL\Query;
use GraphQL\RawObject;

class ProducerOneModel extends GraphQL
{

    private $query;

    /**
     * @inheritDoc
     */
    public function serviceName(): string
    {
        return 'goods';
    }

    public function entityName(): string
    {
        return 'producerOne';
    }

    public function __construct()
    {
        $this->query = (new Query($this->entityName()));

        parent::__construct();
    }

    /**
     * @param array $selectionSet
     * @return $this
     */
    public function setSelectionSet(array $selectionSet): ProducerOneModel
    {
        $this->query->setSelectionSet($selectionSet);

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments): ProducerOneModel
    {
        $this->query->setArguments($arguments);

        return $this;
    }

    /**
     * @param $fieldName
     * @param $value
     * @return $this
     */
    public function setArgumentsWhere($fieldName, $value): ProducerOneModel
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
            ->getResults()['data'][$this->entityName()];
    }
}
