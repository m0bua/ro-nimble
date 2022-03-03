<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Goods;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PaymentMethodsAggregator extends AbstractAggregator
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
    protected function prepare(Collection $ids): Collection
    {
        return $this->model
            ->query()
            ->select([
                'g.id',
                DB::raw("coalesce(json_agg(distinct pm.id), '[]') as payment_method_ids"),
                DB::raw("coalesce(json_agg(distinct pm.parent_id), '[]') as payment_ids")
            ])
            ->from($this->model->getTable(), 'g')
            ->join('goods_payment_method as gpm', 'g.id', 'gpm.goods_id')
            ->join('payment_methods as pm', 'gpm.payment_method_id', 'pm.id')
            ->where('pm.status', 'active')
            ->whereIn('g.id', $ids)
            ->groupBy('g.id')
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(function (object $item) {
                $item->payment_method_ids = $this->decode($item->payment_method_ids);
                $item->payment_ids = $this->decode($item->payment_ids);

                return $item;
            });
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $payments = $this->get($item->id);

        $item->payment_method_ids = $payments->payment_method_ids ?? [];
        $item->payment_ids = $payments->payment_ids ?? [];

        return $item;
    }
}
