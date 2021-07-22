<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionGoodsConstructor;

class ChangePromotionConstructorGoodsProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionGoodsConstructor
     */
    protected PromotionGoodsConstructor $model;

    /**
     * ChangePromotionConstructorGoodsProcessor constructor.
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(PromotionGoodsConstructor $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $constructorId = $message->getField('fields_data.promotion_constructor_id');
        $goodsId = $message->getField('fields_data.goods_id');

        $this->model

            ->updateOrCreate(
                [
                    'constructor_id' => $constructorId,
                    'goods_id' => $goodsId,
                ],
                [
                    'needs_index' => 1,
                    'needs_migrate' => 1,
                ]);

        return Codes::SUCCESS;
    }
}
