<?php

namespace App\Processors\MarketEnterprise\ProductsType;

use App\Models\Eloquent\GoodsCarInfo;
use App\Models\Eloquent\IndexGoods;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        'goods_id',
        'car_trim_id',
    ];

    private IndexGoods $indexGoods;

    /**
     * @param GoodsCarInfo $model
     * @param IndexGoods $indexGoods
     */
    public function __construct(GoodsCarInfo $model, IndexGoods $indexGoods)
    {
        $this->model = $model;
        $this->indexGoods = $indexGoods;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->indexGoods->query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
