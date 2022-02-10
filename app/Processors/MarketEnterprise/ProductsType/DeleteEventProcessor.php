<?php

namespace App\Processors\MarketEnterprise\ProductsType;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsCarInfo;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        'goods_id',
        'car_trim_id',
    ];

    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsCarInfo $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(GoodsCarInfo $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
