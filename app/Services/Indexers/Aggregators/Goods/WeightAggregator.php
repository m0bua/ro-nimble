<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Enums\Filters;
use App\Models\Eloquent\Goods;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class WeightAggregator extends AbstractAggregator
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
            ->select(['c.id', 'c.is_rozetka_top'])
            ->from($this->model->getTable(), 'g')
            ->join('categories as c', 'g.category_id', 'c.id')
            ->whereIn('g.id', $ids)
            ->groupBy('c.id')
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(fn(object $item) => $this->decode($item->is_rozetka_top));
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $isRozetkaTop = $this->get($item->category_id);
        $price = $rank = 99999;
        if ($item->price > 0) {
            $price = \in_array($item->sell_status, [Filters::SELL_STATUS_AVAILABLE, Filters::SELL_STATUS_LIMITED])
                ? 1 : 2;

            switch ($item->sell_status) {
                case Filters::SELL_STATUS_AVAILABLE:
                case Filters::SELL_STATUS_LIMITED:
                    $sellStatus = 1;
                    break;
                case Filters::SELL_STATUS_WAITING_FOR_SUPPLY:
                    $sellStatus = 15;
                    break;
                case Filters::SELL_STATUS_OUT_OF_STOCK:
                    $sellStatus = 16;
                    break;
                case Filters::SELL_STATUS_UNAVAILABLE:
                default:
                    $sellStatus = 17;
                    break;
            }

            switch ($item->state) {
                case Filters::STATE_NEW:
                    $state = 1;
                    break;
                case Filters::STATE_REFURBISHED:
                    $state = 3;
                    break;
                case Filters::STATE_USED:
                default:
                    $state = 7;
                    break;
            }
            $seller = 1;
            if ($isRozetkaTop && $item->seller_id !== 5) {
                $seller = 2;
            }
            $rank = $sellStatus * $state * $seller;
        }

        $item->estimated_weight = [
            [
                'sort'  => 'price',
                'value' => $price
            ],
            [
                'sort'  => 'rank',
                'value' => $rank
            ]
        ];

        return $item;
    }
}
