<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OptionPluralAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var GoodsOptionPlural
     */
    private GoodsOptionPlural $model;

    /**
     * @param GoodsOptionPlural $model
     */
    public function __construct(GoodsOptionPlural $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        return $this->model
            ->query()
            ->select([
                'gop.goods_id',
                DB::raw("coalesce(json_agg(DISTINCT o.id) filter (WHERE o.id IS NOT NULL), '[]') as options"),
                DB::raw("coalesce(json_agg(DISTINCT CASE WHEN ov.parent_id = 0 THEN ov.id WHEN ov.parent_id is null THEN ov.id ELSE ov.parent_id END) filter (WHERE ov.status IS NOT NULL), '[]') AS option_values"),
            ])
            ->from($this->model->getTable(), 'gop')
            ->join('options as o', 'gop.option_id', 'o.id')
            ->join('option_values as ov', 'gop.value_id', 'ov.id')
            ->where('o.state', 'active')
            ->where('ov.status', 'active')
            ->whereIn('o.option_record_comparable', [
                'main',
                'bottom'
            ])
            ->whereIn('o.type', [
                'List',
                'ComboBox',
                'ListValues',
                'CheckBoxGroup',
                'CheckBoxGroupValues',
            ])
            ->whereIn('gop.goods_id', $ids)
            ->groupBy('gop.goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(function (object $item) {
                $item->options = $this->decode($item->options);
                $item->option_values = $this->decode($item->option_values);

                return $item;
            });
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $optionsPlural = $this->get($item->id);

        $item->options = $optionsPlural->options ?? [];
        $item->option_values = $optionsPlural->option_values ?? [];

        return $item;
    }
}
