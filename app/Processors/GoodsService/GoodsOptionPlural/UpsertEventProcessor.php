<?php

namespace App\Processors\GoodsService\GoodsOptionPlural;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\UpsertProcessor;
use App\Services\Buffers\RedisGoodsBufferService;
use Illuminate\Support\Facades\Redis;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'goods_id',
        'option_id',
        'value_id',
    ];

    /**
     * @var RedisGoodsBufferService $goodsBuffer
     */
    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param GoodsOptionPlural $model
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(GoodsOptionPlural $model, RedisGoodsBufferService $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        $data = parent::prepareData();
        $data['needs_index'] = 1;

        return $data;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
