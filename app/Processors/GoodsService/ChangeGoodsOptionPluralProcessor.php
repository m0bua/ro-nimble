<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionPlural;

class ChangeGoodsOptionPluralProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var GoodsOptionPlural
     */
    protected GoodsOptionPlural $model;

    /**
     * Goods model
     *
     * @var Goods
     */
    protected Goods $goods;

    /**
     * ChangeGoodsOptionPluralProcessor constructor.
     * @param GoodsOptionPlural $model
     * @param Goods $goods
     */
    public function __construct(GoodsOptionPlural $model, Goods $goods)
    {
        $this->model = $model;
        $this->goods = $goods;
    }

    public function processMessage(MessageInterface $message): int
    {
        $data = (array)$message->getField('data');

        $this->model
            ->whereGoodsId($data['goods_id'])
            ->whereOptionId($data['option_id'])
            ->whereValueId($data['value_id'])
            ->update([
                'needs_index' => 1,
            ]);

        $goods = $this->goods
            ->whereId($data['goods_id'])
            ->first(['needs_index']);

        if (!$goods || $goods->needs_index != 1) {
            $this->goods
                ->write()
                ->whereId($data['goods_id'])
                ->update(['needs_index' => 1]);
        }

        return Codes::SUCCESS;
    }
}
