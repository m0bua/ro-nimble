<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsOptionBoolean;
use App\Models\Eloquent\GoodsOptionNumber;
use App\Models\Eloquent\IndexGoods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangeGoodsOptionProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy  = ['goods_id', 'option_id'];

    private Goods $goods;
    private GoodsOptionBoolean $boolean;
    private GoodsOptionNumber $number;
    private IndexGoods $indexGoods;

    /**
     * @param GoodsOptionBoolean $boolean
     * @param GoodsOptionNumber $number
     * @param IndexGoods $indexGoods
     */
    public function __construct(GoodsOptionBoolean $boolean, GoodsOptionNumber $number, IndexGoods $indexGoods)
    {
        $this->boolean = $boolean;
        $this->number = $number;
        $this->indexGoods = $indexGoods;
    }

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
                $this->number->upsert(
                    [
                        'goods_id' => $data['goods_id'],
                        'option_id' => $data['option_id'],
                        'value' => $data['value']
                    ],
                    $uniqueBy,
                    $update
                );
                break;
        }

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
