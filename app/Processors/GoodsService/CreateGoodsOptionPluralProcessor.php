<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionPlural;

class CreateGoodsOptionPluralProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var GoodsOptionPlural
     */
    protected GoodsOptionPlural $model;

    protected Goods $goods;

    /**
     * CreateGoodsOptionPluralProcessor constructor.
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

            ->create([
                'goods_id' => $data['goods_id'],
                'option_id' => $data['option_id'],
                'value_id' => $data['value_id'],
            ]);

        $this->goods

            ->where('id', $data['goods_id'])
            ->update(['needs_index' => 1]);

        return Codes::SUCCESS;
    }
}
