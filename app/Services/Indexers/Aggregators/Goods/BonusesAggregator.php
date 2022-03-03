<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Bonus;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class BonusesAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var Bonus
     */
    private Bonus $model;

    /**
     * @param Bonus $model
     */
    public function __construct(Bonus $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function get(int $key): int
    {
        return parent::get($key) ?? 0;
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        return $this->model
            ->query()
            ->selectRaw('distinct goods_id')
            ->selectRaw('bonus_charge_pcs')
            ->whereIn('goods_id', $ids)
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(fn(object $item) => $item->bonus_charge_pcs);
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->bonus_charge_pcs = $this->get($item->id);

        return $item;
    }
}
