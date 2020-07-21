<?php


namespace App\Models\Elastic\Promotions;


use App\Models\Elastic\Elastic;

class PromotionsElastic extends Elastic
{

    /**
     * @inheritDoc
     */
    public function indexName(): string
    {
        return 'promotions';
    }
}
