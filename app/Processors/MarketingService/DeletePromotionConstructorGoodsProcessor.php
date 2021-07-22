<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionGoodsConstructor;

class DeletePromotionConstructorGoodsProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionGoodsConstructor
     */
    protected PromotionGoodsConstructor $model;

    /**
     * DeletePromotionConstructorGoodsProcessor constructor.
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(PromotionGoodsConstructor $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $this->model

            ->where('constructor_id', $message->getField('fields_data.promotion_constructor_id'))
            ->where('goods_id', $message->getField('fields_data.goods_id'))
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
