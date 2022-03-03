<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Goods;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProducerTitlesAggregator extends AbstractAggregator
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
    public function get(int $key): string
    {
        return parent::get($key) ?? '';
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
                DB::raw("coalesce(pt.value, '') as producer_title"),
            ])
            ->from($this->model->getTable(), 'g')
            ->join('producer_translations as pt', 'g.producer_id', 'pt.producer_id')
            ->whereIn('g.id', $ids)
            ->where('pt.lang', 'ru')
            ->toBase()
            ->get()
            ->keyBy('id')
            ->transform(fn(object $item) => $item->producer_title);
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->producer_title = $this->get($item->id);

        return $item;
    }
}
