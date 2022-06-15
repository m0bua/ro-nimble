<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\Label;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class LabelsAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var Label
     */
    private Label $model;

    /**
     * @param Label $model
     */
    public function __construct(Label $model)
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
            ->selectRaw("coalesce(json_agg(distinct label_id) filter (where label_id is not null), '[]') as goods_labels_ids")
            ->from($this->model->getTable(), 'l')
            ->join('goods_label as gl', 'gl.label_id', 'l.id')
            ->whereIn('goods_id', $ids)
            ->groupBy('goods_id')
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(fn (object $item) => $this->decode($item->goods_labels_ids));
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $item->goods_labels_ids = $this->get($item->id);

        return $item;
    }
}
