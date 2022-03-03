<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Goods;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function collect;

class PromotionsAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var Goods
     */
    private Goods $model;

    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
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
        $groupPromotions = $this->model
            ->query()
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(distinct pc.promotion_id), '[]') as promotion_id"),
            ])
            ->from($this->model->getTable(), 'g')
            ->join('promotion_groups_constructors as pgrc', 'pgrc.group_id', 'g.group_id')
            ->join('promotion_constructors as pc', 'pc.id', 'pgrc.constructor_id')
            ->whereIn('g.id', $ids)
            ->groupBy('g.id')
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(fn(object $item) => collect($this->decode($item->promotion_id)));

        return $this->model
            ->query()
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(distinct pc.promotion_id), '[]') as promotion_id"),
            ])
            ->from($this->model->getTable(), 'g')
            ->join('promotion_goods_constructors as pgc', 'pgc.goods_id', 'g.id')
            ->join('promotion_constructors as pc', 'pc.id', 'pgc.constructor_id')
            ->whereIn('g.id', $ids)
            ->groupBy('g.id')
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(
                fn(object $item) => collect($this->decode($item->promotion_id))
                    ->merge($groupPromotions[$item->id] ?? [])
                    ->unique()
                    ->toArray()
            );
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->promotion_ids = $this->get($item->id);

        return $item;
    }
}
