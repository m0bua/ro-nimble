<?php


namespace App\Models\Elastic\Promotions;


use App\Models\Elastic\Elastic;

abstract class PromotionsElastic extends Elastic
{

    /**
     * @inheritDoc
     */
    public function indexName(): string
    {
        return 'promotions';
    }
}
