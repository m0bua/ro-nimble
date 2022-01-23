<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\GoodsOption;
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

    protected Option $model;

    protected GoodsOption $goodsOption;

    protected GoodsOptionPlural $goodsOptionPlural;

    protected OptionValue $optionValue;

    /**
     * DeleteOptionEntityProcessor constructor.
     * @param Option $model
     * @param GoodsOption $goodsOption
     */
    public function __construct(
        Option $model,
        GoodsOption $goodsOption,
        GoodsOptionPlural $goodsOptionPlural,
        OptionValue $optionValue
    )
    {
        $this->model = $model;
        $this->goodsOption = $goodsOption;
        $this->goodsOptionPlural = $goodsOptionPlural;
        $this->optionValue = $optionValue;
    }

    protected function afterProcess(): void
    {
        $goQuery = $this->goodsOption
            ->query()
            ->where('option_id', '=', $this->data['id']);

        $gopQuery = $this->goodsOptionPlural
            ->query()
            ->where('option_id', '=', $this->data['id']);

        $goGoods = $goQuery
            ->select('goods_id as id')
            ->distinct()
            ->get();

        $gopGoods = $gopQuery
            ->select('goods_id as id')
            ->distinct()
            ->get();

        $goods = $goGoods->merge($gopGoods)->unique('id')->toArray();

        IndexGoods::query()->insertOrIgnore($goods);

        $goQuery->delete();
        $gopQuery->delete();
        $this->optionValue->query()
            ->where('option_id', '=', $this->data['id'])
            ->delete();
    }
}
