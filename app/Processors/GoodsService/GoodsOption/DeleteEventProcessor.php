<?php

namespace App\Processors\GoodsService\GoodsOption;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\GoodsOptionBoolean;
use App\Models\Eloquent\GoodsOptionNumber;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    private GoodsOptionBoolean $boolean;
    private GoodsOptionNumber $number;
    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsOptionBoolean $boolean
     * @param GoodsOptionNumber $number
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(
        GoodsOptionBoolean $boolean,
        GoodsOptionNumber $number,
        GoodsBuffer $goodsBuffer
    ) {
        $this->boolean = $boolean;
        $this->number = $number;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @inheritDoc
     */
    protected function deleteModel(): void
    {
        $data = $this->prepareData();

        switch ($data['type']) {
            case 'bool':
                $this->boolean->query()
                    ->where([
                        'goods_id' => $data['goods_id'],
                        'option_id' => $data['option_id'],
                    ])
                    ->delete();
                break;
            case 'number':
                $this->number->query()
                    ->where([
                        'goods_id' => $data['goods_id'],
                        'option_id' => $data['option_id'],
                    ])
                    ->delete();
                break;
        }
    }

    /**
     * @inheritDoc
     */
    protected function deleteTranslations(): void
    {
        //
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
