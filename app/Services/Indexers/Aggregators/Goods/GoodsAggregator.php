<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Goods;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use App\Services\Indexers\Aggregators\Aggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use function collect;

class GoodsAggregator extends AbstractAggregator
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
     * Make other aggregators
     *
     * @return Collection|Aggregator[]
     */
    private function getOtherAggregators(): Collection
    {
        return collect([
            OptionCheckedAggregator::class,
            OptionPluralAggregator::class,
            OptionSlidersAggregator::class,
            PromotionsAggregator::class,
            PaymentMethodsAggregator::class,
            BonusesAggregator::class,
            CarsInfoAggregator::class,
            ProducerTitlesAggregator::class,
            CommentsAggregator::class,
            LabelsAggregator::class,
        ])->mapWithKeys(fn(string $class) => [$class => App::make($class)]);
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        $aggregators = $this->getOtherAggregators()
            ->transform(fn(Aggregator $aggregator) => $aggregator->aggregate($ids));

        return $this->model
            ->query()
            ->select([
                'id',
                'category_id',
                'group_id',
                'is_group_primary',
                'price',
                'producer_id',
                'seller_id',
                DB::raw("(case
                        when merchant_id = 1 then 1
                        when merchant_id = 2 then 1
                        when merchant_id = 14 then 1
                        when merchant_id = 20 then 1
                        when merchant_id = 51 then 1
                        when merchant_id = 67 then 1
                        when merchant_id = 43 then 1
                        when merchant_id = 58 then 1
                        when merchant_id = 64 then 1
                        when merchant_id = 56 then 1
                        when merchant_id = 0 then 0
                        else 2
                    end) as merchant_type"
                ),
                'series_id',
                'sell_status',
                'state',
                'status_inherited',
                'country_code',
                'rank',
                'order',
                DB::raw("to_json(string_to_array(trim('.' from mpath), '.')) as categories_path"),
            ])
            ->whereIn('id', $ids)
            ->whereIn('status', [Goods::STATUS_ACTIVE, Goods::STATUS_CONFIGURABLE_BY_SERVICES])
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(function (object $item) use ($aggregators) {
                $item->group_token = $item->group_id ? "g$item->group_id" : "p$item->id";
                $item->categories_path = collect($this->decode($item->categories_path))
                    ->map(fn($i) => (int)$i)
                    ->toArray();

                foreach ($aggregators as $aggregator) {
                    $item = $aggregator->decorate($item);
                }

                return $item;
            });
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        return $item;
    }
}
