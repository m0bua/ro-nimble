<?php

namespace App\Processors\GoodsService;

use App\Console\Commands\IndexRefill;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionPlural;
use App\Models\Eloquent\IndexGoods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;
use Illuminate\Support\Facades\Artisan;

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
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
