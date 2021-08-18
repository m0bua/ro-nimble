<?php


namespace App\Processors\GoodsService;


use App\Console\Commands\IndexRefill;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOption;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;
use Illuminate\Support\Facades\Artisan;

class CreateGoodsOptionProcessor extends AbstractProcessor
{
    use WithCreate;

    public static ?array $compoundKey = [
        'goods_id',
        'option_id',
        'type',
    ];

    protected GoodsOption $model;

    protected Goods $goods;

    /**
     * CreateGoodsOptionProcessor constructor.
     * @param GoodsOption $model
     * @param Goods $goods
     */
    public function __construct(GoodsOption $model, Goods $goods)
    {
        $this->model = $model;
        $this->goods = $goods;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        Artisan::call(IndexRefill::class, ['--goods-ids' => $this->data['goods_id']]);
    }
}
