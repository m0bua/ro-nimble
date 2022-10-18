<?php

namespace App\Processors\GoodsService\GoodsOption;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsOptionBoolean;
use App\Models\Eloquent\GoodsOptionNumber;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'goods_id',
        'option_id'
    ];

    private GoodsOptionBoolean $boolean;
    private GoodsOptionNumber $number;
    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsOptionBoolean $boolean
     * @param GoodsOptionNumber $number
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(
        GoodsOptionBoolean   $boolean,
        GoodsOptionNumber    $number,
        GoodsBuffer $goodsBuffer
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
                if (trim($data['value']) === "0") {
                    $this->boolean->query()
                        ->where([
                            'goods_id' => $data['goods_id'],
                            'option_id' => $data['option_id'],
                        ])
                        ->delete();
                } else {
                    $this->boolean->upsert(
                        [
                            'goods_id' => $data['goods_id'],
                            'option_id' => $data['option_id'],
                            'need_delete' => $data['need_delete'],],
                        $uniqueBy,
                        $update
                    );
                }
                break;
            case 'number':
                if (trim($data['value']) === "") {
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
                            'value' => $data['value'],
                            'need_delete' => $data['need_delete'],
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
