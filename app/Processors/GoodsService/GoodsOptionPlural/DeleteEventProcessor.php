<?php

namespace App\Processors\GoodsService\GoodsOptionPlural;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected array $compoundKey = [
        'goods_id',
        'option_id',
        'value_id',
    ];

    protected string $dataRoot;

    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsOptionPlural $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(GoodsOptionPlural $model, GoodsBuffer $goodsBuffer)
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
