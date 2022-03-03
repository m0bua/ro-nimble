<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\GoodsCarInfo;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class CarsInfoAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var GoodsCarInfo
     */
    private GoodsCarInfo $model;

    /**
     * @param GoodsCarInfo $model
     */
    public function __construct(GoodsCarInfo $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function get(int $key): array
    {
        return parent::get($key) ?? [
                'car_trim_id' => [],
                'car_brand_id' => [],
                'car_model_id' => [],
                'car_year_id' => []
            ];
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        return $this->model
            ->query()
            ->select('goods_id')
            ->selectRaw("coalesce(json_agg(DISTINCT jsonb_build_object(
                'car_trim_id', car_trim_id,
                'car_brand_id', car_brand_id,
                'car_model_id', car_model_id,
                'car_year_id', car_year_id)), '[]') AS car_infos")
            ->whereIn('goods_id', $ids)
            ->groupBy('goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(function (object $item) {
                $item = collect($this->decode($item->car_infos));

                return [
                    'car_trim_id' => $item->pluck('car_trim_id')->unique()->toArray(),
                    'car_brand_id' => $item->pluck('car_brand_id')->unique()->toArray(),
                    'car_model_id' => $item->pluck('car_model_id')->unique()->toArray(),
                    'car_year_id' => $item->pluck('car_year_id')->unique()->toArray(),
                ];
            });
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $carsInfo = $this->get($item->id);

        $item->car_trim_id = $carsInfo['car_trim_id'];
        $item->car_brand_id = $carsInfo['car_brand_id'];
        $item->car_model_id = $carsInfo['car_model_id'];
        $item->car_year_id = $carsInfo['car_year_id'];

        return $item;
    }
}
