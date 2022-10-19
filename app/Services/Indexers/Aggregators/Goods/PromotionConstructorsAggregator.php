<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PromotionConstructorsAggregator extends AbstractAggregator
{
    private const DEFAULT_ORDER = 16777215;

    /**
     * Eloquent goods model
     *
     * @var PromotionGoodsConstructor
     */
    private PromotionGoodsConstructor $model;

    /**
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(PromotionGoodsConstructor $model)
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
        $queryOrder = $this->model
            ->query()
            ->select(['pgci.order'])
            ->from($this->model->getTable(), 'pgci')
            ->join(PromotionConstructor::getModel()->getTable() . ' as pci', 'pci.id', 'pgci.constructor_id')
            ->where('pgci.goods_id', '=', DB::raw('pgc.goods_id'))
            ->where('pci.promotion_id', '=', DB::raw('pc.promotion_id'))
            ->orderBy('pgci.updated_at', 'desc')
            ->limit(1);

        $queryTmp = $this->model
            ->query()
            ->select(['pgc.goods_id'])
            ->selectRaw("json_build_object('id', pc.promotion_id, 'order', COALESCE(({$queryOrder->toSql()}), "
                . self::DEFAULT_ORDER . ")) as promotion")
            ->from($this->model->getTable(), 'pgc')
            ->join(PromotionConstructor::getModel()->getTable() . ' as pc', 'pc.id', 'pgc.constructor_id')
            ->whereIn('pgc.goods_id', $ids)
            ->groupBy('pgc.goods_id', 'pc.promotion_id');

        return $this->model
            ->query()
            ->select(['tmp.goods_id'])
            ->selectRaw("json_agg(promotion) as promotion")
            ->from($queryTmp, 'tmp')
            ->groupBy('tmp.goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(fn(object $item) => $this->decode($item->promotion));
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->promotion = $this->get($item->id);

        return $item;
    }
}
