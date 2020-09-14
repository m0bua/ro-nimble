<?php


namespace App\Models\GraphQL;


class OptionOneModel extends GraphQL
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
        return 'optionOne';
    }
}
