<?php


namespace App\Models\GraphQL;

class ProducerOneModel extends GraphQL
{
    /**
     * @inheritDoc
     */
    public function serviceName(): string
    {
        return 'goods';
    }

    /**
     * @inheritDoc
     */
    public function entityName(): string
    {
        return 'producerOne';
    }
}
