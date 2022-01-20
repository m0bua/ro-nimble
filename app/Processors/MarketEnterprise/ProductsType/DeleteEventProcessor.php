<?php

namespace App\Processors\MarketEnterprise\ProductsType;

use App\Models\Eloquent\GoodsCarInfo;
use App\Models\Eloquent\IndexGoods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteEventProcessor extends AbstractProcessor
{
    use WithDelete;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'goods_id',
        'car_trim_id',
    ];

    /**
     * Model
     *
     * @var GoodsCarInfo
     */
    protected GoodsCarInfo $model;

    /**
     * @param GoodsCarInfo $model
     */
    public function __construct(GoodsCarInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
