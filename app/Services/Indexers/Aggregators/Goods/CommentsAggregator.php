<?php

namespace App\Services\Indexers\Aggregators\Goods;

use App\Models\Eloquent\GoodsComment;
use App\Services\Indexers\Aggregators\AbstractAggregator;
use Illuminate\Support\Collection;

class CommentsAggregator extends AbstractAggregator
{
    /**
     * Eloquent goods model
     *
     * @var GoodsComment
     */
    private GoodsComment $model;

    /**
     * @param GoodsComment $model
     */
    public function __construct(GoodsComment $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function get(int $key): float
    {
        return parent::get($key) ?? 0.0;
    }

    /**
     * @inheritDoc
     */
    protected function prepare(Collection $ids): Collection
    {
        $groupComments = $this->model
            ->query()
            ->select('goods_group_id')
            ->selectRaw('round(sum(summ_marks)::decimal / coalesce(nullif(sum(count_marks)::decimal, 0), 1), 1) as group_comment_avg_marks')
            ->where('goods_group_id', '!=', 0)
            ->whereIn('goods_id', $ids)
            ->groupBy('goods_group_id')
            ->toBase()
            ->get()
            ->keyBy('goods_group_id')
            ->transform(fn(object $item) => $item->group_comment_avg_marks);

        return $this->model
            ->query()
            ->select(['goods_id', 'goods_group_id'])
            ->selectRaw('round(summ_marks::decimal / coalesce(nullif(count_marks::decimal, 0), 1), 1) as comment_avg_marks')
            ->whereIn('goods_id', $ids)
            ->toBase()
            ->get()
            ->keyBy('goods_id')
            ->transform(function (object $item) use ($groupComments) {
                $item->group_comment_avg_marks = $groupComments[$item->goods_group_id] ?? 0;
                return $item;
            });
    }

    /**
     * @inheritDoc
     */
    public function decorate(object $item): object
    {
        $commentMarks = $this->get($item->id);

        $item->comment_avg_marks = $commentMarks->comment_avg_marks ?? 0;
        $item->group_comment_avg_marks = $commentMarks->group_comment_avg_marks ?? 0;

        return $item;
    }
}
