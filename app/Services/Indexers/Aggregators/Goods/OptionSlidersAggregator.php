<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\GoodsOptionNumber;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OptionSlidersAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var GoodsOptionNumber
     */
    private GoodsOptionNumber $model;

    /**
     * @param GoodsOptionNumber $model
     */
    public function __construct(GoodsOptionNumber $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function get(int $key): array
    {
        return parent::get($key) ?? [];
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        return $this->model
            ->query()
            ->select([
                'gon.goods_id',
                DB::raw("coalesce(json_agg(distinct jsonb_build_object('id', o.id, 'value', gon.value)) filter (where gon.value is not null), '[]') as option_sliders"),
            ])
            ->from($this->model->getTable(), 'gon')
            ->join('options as o', 'gon.option_id', 'o.id')
            ->where('o.state', 'active')
            ->whereIn('gon.goods_id', $ids)
            ->groupBy('gon.goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(fn(object $item) => $this->decode($item->option_sliders));
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->option_sliders = $this->get($item->id);

        return $item;
    }
}
