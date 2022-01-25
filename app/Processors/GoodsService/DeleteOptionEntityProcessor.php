<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\GoodsOptionBoolean;
use App\Models\Eloquent\GoodsOptionNumber;
use App\Models\Eloquent\GoodsOptionPlural;
use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteOptionEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = false;

    public static ?string $dataRoot = null;

    private Option $model;
    private GoodsOptionPlural $goodsOptionPlural;
    private OptionValue $optionValue;
    private IndexGoods $indexGoods;
    private GoodsOptionBoolean $boolean;
    private GoodsOptionNumber $number;

    /**
     * DeleteOptionEntityProcessor constructor.
     *
     * @param Option $model
     * @param GoodsOptionBoolean $boolean
     * @param GoodsOptionNumber $number
     * @param GoodsOptionPlural $goodsOptionPlural
     * @param OptionValue $optionValue
     */
    public function __construct(
        Option $model,
        GoodsOptionBoolean $boolean,
        GoodsOptionNumber $number,
        GoodsOptionPlural $goodsOptionPlural,
        OptionValue $optionValue
    )
    {
        $this->model = $model;
        $this->boolean = $boolean;
        $this->number = $number;
        $this->goodsOptionPlural = $goodsOptionPlural;
        $this->optionValue = $optionValue;
    }

    protected function afterProcess(): void
    {
        $boolQuery = $this->boolean
            ->query()
            ->where('option_id', '=', $this->data['id']);

        $numQuery = $this->number
            ->query()
            ->where('option_id', '=', $this->data['id']);

        $gopQuery = $this->goodsOptionPlural
            ->query()
            ->where('option_id', '=', $this->data['id']);

        $boolGoods = $boolQuery
            ->select('goods_id as id')
            ->distinct()
            ->get();

        $numGoods = $numQuery
            ->select('goods_id as id')
            ->distinct()
            ->get();

        $gopGoods = $gopQuery
            ->select('goods_id as id')
            ->distinct()
            ->get();

        $goods = $boolGoods
            ->merge($numGoods)
            ->merge($gopGoods)
            ->unique('id')
            ->toArray();

        IndexGoods::query()->insertOrIgnore($goods);

        $boolQuery->delete();
        $numQuery->delete();
        $gopQuery->delete();
        $this->optionValue->query()
            ->where('option_id', '=', $this->data['id'])
            ->delete();
    }
}
