<?php

namespace App\Processors\GoodsService\Goods;

use App\Models\Eloquent\Goods;
use App\Processors\UpsertProcessor;
use App\Services\Buffers\RedisGoodsBufferService;

class UpsertEventProcessor extends UpsertProcessor
{
    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param Goods $model
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(Goods $model, RedisGoodsBufferService $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @return void
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['id']);
    }
}
