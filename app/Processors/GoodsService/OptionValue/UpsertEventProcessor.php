<?php

namespace App\Processors\GoodsService\OptionValue;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionValue;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * Maximum count goods for one bulk indexing
     */
    protected int $maxBatch;

    /**
     * @var GoodsBuffer
     */
    protected GoodsBuffer $goodsBuffer;

    /**
     * @param OptionValue $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(OptionValue $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(): array
    {
        $data = parent::prepareData();

        if (isset($data['show_value_in_short_set'])) {
            $data['show_value_in_short_set'] = $data['show_value_in_short_set'] === 'true' ? 1 : 0;
        }

        return $data;
    }

    /**
     * @inerhitDoc
     *
     * @param $uniqueBy
     * @param array|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();

        $oldData = OptionValue::select(['o.state', 'ov.status'])
            ->from(OptionValue::getModel()->getTable() . ' as ov')
            ->join('options as o', 'o.id', 'ov.option_id')
            ->where('ov.id', '=', $data['id'])
            ->first();

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        if (empty($oldData)) {
            $option = Option::select(['o.state'])
                ->from(Option::getModel()->getTable(), 'o')
                ->where('o.id', '=', $data['option_id'])
                ->first();

            if(empty($option) || $option['state'] === Option::STATE_LOCKED) {
                return true;
            }
        }

        if (
            is_null($oldData)
            /** Додавання значення опції в індекс */
            || (\in_array($oldData['status'], [OptionValue::STATUS_LOCKED, OptionValue::STATUS_NOT_USE])
                && OptionValue::STATUS_ACTIVE === $data['status'])
            /** Видалення значення опції з індексу */
            || (OptionValue::STATUS_ACTIVE === $oldData['status']
                && \in_array($data['status'], [OptionValue::STATUS_LOCKED, OptionValue::STATUS_NOT_USE]))
        ) {
            if (!$data['parent_id']) {
                $optionValues = $this->model->query()
                    ->select(['id'])
                    ->where('parent_id', '=', $data['id'])
                    ->pluck('id')
                    ->all();

                \array_push($optionValues, $data['id']);
            } else {
                $optionValues = [$data['id']];
            }

            $query = $this->model->query()
                ->select('g.id')
                ->from('option_values as ov')
                ->join('options as o', 'o.id', 'ov.option_id')
                ->join('goods_options_plural as gt', 'ov.id', '=', 'gt.value_id')
                ->join('goods as g', 'g.id', '=', 'gt.goods_id')
                ->leftJoin('categories as c', 'c.id', 'g.category_id')
                ->leftJoin('categories as cc', function ($join) {
                    $join->on('cc.left_key', '>=', 'c.left_key')
                        ->whereColumn('cc.right_key', '<=', 'c.right_key');
                })
                ->leftJoin('option_settings as os', function ($join) {
                    $join->on('os.category_id', '=', 'cc.id')
                        ->whereColumn('os.option_id', '=', 'o.id');
                })
                ->leftJoin('option_settings as os0', function ($join) {
                    $join->where('os0.category_id', '=', 0)
                        ->whereColumn('os0.option_id', '=', 'o.id');
                })
                ->whereIn('o.state', [Option::STATE_ACTIVE])
                ->whereIn('ov.id',  $optionValues)
                ->whereIn('g.status', [Goods::STATUS_ACTIVE, Goods::STATUS_CONFIGURABLE_BY_SERVICES])
                ->whereNotIn('g.sell_status', [Goods::SELL_STATUS_HIDDEN, Goods::SELL_STATUS_ARCHIVE])
                ->whereIn('g.status_inherited', [Goods::STATUS_INHERITED_ACTIVE])
                ->where(function($q)  {
                    $q->where(function ($qq) {
                        $qq->where(function ($qs) {
                            $qs->whereNotIn('os.status', ['call-center', 'locked', 'passive'])
                                ->whereIn('os.comparable', ['bottom', 'main']);
                        })->orWhere(function ($qs) {
                            $qs->whereNotIn('os0.status', ['call-center', 'locked', 'passive'])
                                ->whereIn('os0.comparable', ['bottom', 'main']);
                        });
                    })->orWhere(function ($qq) {
                        $qq->whereNull('os.status')
                            ->whereNull('os0.status');
                    });
                });

            foreach ($query->trueCursor($this->maxBatch) as $goods) {
                $this->goodsBuffer->radd($goods->pluck('id')->toArray());
            }
        }

        return true;
    }
}
