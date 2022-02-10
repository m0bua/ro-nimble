<?php

namespace App\Processors\GoodsService\GoodsOption;

use App\Models\Eloquent\GoodsOptionBoolean;
use App\Models\Eloquent\GoodsOptionNumber;
use App\Processors\UpsertProcessor;
use App\Services\Buffers\RedisGoodsBufferService;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'goods_id',
        'option_id'
    ];

    private GoodsOptionBoolean $boolean;
    private GoodsOptionNumber $number;
    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param GoodsOptionBoolean $boolean
     * @param GoodsOptionNumber $number
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(
        GoodsOptionBoolean $boolean,
        GoodsOptionNumber $number,
        RedisGoodsBufferService $goodsBuffer
    )
    {
        $this->boolean = $boolean;
        $this->number = $number;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @inheritDoc
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();

        switch ($data['type']) {
            case 'bool':
                $this->boolean->upsert(
                    [
                        'goods_id' => $data['goods_id'],
                        'option_id' => $data['option_id']
                    ],
                    $uniqueBy,
                    $update
                );
                break;
            case 'number':
                if (trim($data['value']) == "") {
                    $this->number->query()
                        ->where([
                            'goods_id' => $data['goods_id'],
                            'option_id' => $data['option_id'],
                        ])
                        ->delete();
                } else {
                    $this->number->upsert(
                        [
                            'goods_id' => $data['goods_id'],
                            'option_id' => $data['option_id'],
                            'value' => $data['value']
                        ],
                        $uniqueBy,
                        $update
                    );
                }
                break;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
