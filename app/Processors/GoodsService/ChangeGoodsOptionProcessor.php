<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOption;

class ChangeGoodsOptionProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var GoodsOption
     */
    protected GoodsOption $model;

    protected Goods $goods;

    /**
     * ChangeGoodsOptionProcessor constructor.
     * @param GoodsOption $model
     * @param Goods $goods
     */
    public function __construct(GoodsOption $model, Goods $goods)
    {
        $this->model = $model;
        $this->goods = $goods;
    }

    public function processMessage(MessageInterface $message): int
    {
        $data = (array)$message->getField('data');

        $this->model

            ->where('goods_id', $data['goods_id'])
            ->where('option_id', $data['option_id'])
            ->update([
                'type' => $data['type'],
                'value' => $data['value'],
                'needs_index' => 1,
            ]);

        $goods = $this->goods
            ->where('id', $data['goods_id'])
            ->first(['needs_index']);

        if (!$goods || $goods->needs_index != 1) {
            $this->goods

                ->where('id', $data['goods_id'])
                ->update(['needs_index' => 1]);
        }

        return Codes::SUCCESS;
    }
}
