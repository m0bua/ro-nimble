<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\GoodsOptionBoolean;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class OptionCheckedAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var GoodsOptionBoolean
     */
    private GoodsOptionBoolean $model;

    /**
     * @param GoodsOptionBoolean $model
     */
    public function __construct(GoodsOptionBoolean $model)
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
            ->select('goods_id')
            ->selectRaw("coalesce(json_agg(distinct o.id) filter (where o.id is not null), '[]') as option_checked")
            ->from($this->model->getTable(), 'gob')
            ->join('options as o', 'gob.option_id', 'o.id')
            ->where('o.state', 'active')
            ->whereIn('o.option_record_comparable', ['main', 'bottom'])
            ->whereIn('goods_id', $ids)
            ->groupBy('goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(fn(object $item) => $this->decode($item->option_checked));
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->option_checked = $this->get($item->id);

        return $item;
    }
}
