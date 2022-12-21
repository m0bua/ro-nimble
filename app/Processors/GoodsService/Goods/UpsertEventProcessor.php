<?php

namespace App\Processors\GoodsService\Goods;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    private GoodsBuffer $goodsBuffer;

    /**
     * @param Goods $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(Goods $model, GoodsBuffer $goodsBuffer)
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
