<?php

namespace App\Processors\MarketEnterprise\ProductsType;

use App\Models\Eloquent\GoodsCarInfo;
use App\Processors\DeleteProcessor;
use App\Services\Buffers\RedisGoodsBufferService;
use Illuminate\Support\Facades\Redis;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';

    protected array $compoundKey = [
        'goods_id',
        'car_trim_id',
    ];

    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param GoodsCarInfo $model
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(GoodsCarInfo $model, RedisGoodsBufferService $goodsBuffer)
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
