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
        if ($this->data['group_id'] > 0) {
            $this->goodsBuffer->radd(
                Goods::getAllGroupGoods($this->data['id'])
                    ->pluck('id')
                    ->toArray()
            );
        } else {
            $this->goodsBuffer->radd(
                Goods::where('id', $this->data['id'])
                    ->pluck('id')
                    ->toArray()
            );
        }
    }
}
