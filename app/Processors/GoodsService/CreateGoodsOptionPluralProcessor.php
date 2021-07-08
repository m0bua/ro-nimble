<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateGoodsOptionPluralProcessor extends AbstractProcessor
{
    use WithCreate;

    public static ?array $compoundKey = [
        'goods_id',
        'option_id',
        'value_id',
    ];

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

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goods->whereId($this->data['goods_id'])->update([
            'needs_index' => 1,
        ]);
    }
}
