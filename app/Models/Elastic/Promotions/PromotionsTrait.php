<?php
namespace App\Models\Elastic\Promotions;

trait PromotionsTrait
{
    /**
     * @inheritDoc
     */
    public function indexName(): string
    {
        return 'promotions';
    }
}
