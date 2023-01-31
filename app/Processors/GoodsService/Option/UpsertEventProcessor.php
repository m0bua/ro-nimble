<?php

namespace App\Processors\GoodsService\Option;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\Option;
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
     * @param Option $model
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(Option $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    /**
     * @inerhitDoc
     *
     * @param $uniqueBy
     * @param array|null $update
     * @return bool
     * @throws \JsonException
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $oldOptionState = Option::select(['state'])
            ->where('id', '=', $data['id'])
            ->pluck('state')
            ->first();

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        if (
            /** Додавання опції в індекс */
            (Option::STATE_LOCKED === $oldOptionState && Option::STATE_ACTIVE === $data['state'])
            /** Видалення опції з індексу */
            || (Option::STATE_ACTIVE === $oldOptionState && Option::STATE_LOCKED === $data['state'])
        ) {
            $query = $this->model->query()
                ->select('g.id')
                ->distinct()
                ->from('options as o');

            switch ($data['type']) {
                case Option::TYPE_CHECKBOX:
                    $query = $query->join('goods_option_booleans as gt', 'gt.option_id', 'o.id');
                    break;
                case Option::TYPE_INTEGER:
                case Option::TYPE_DECIMAL:
                    $query = $query->join('goods_option_numbers as gt', 'gt.option_id', 'o.id');
                    break;
                default:
                    $query = $query->join('goods_options_plural as gt', 'gt.option_id', 'o.id')
                        ->join('option_values as ov', function ($join) {
                            $join->on('ov.id', '=', 'gt.value_id')
                                ->where('ov.status', '=', 'active');
                        });
            }

            $query = $query->join('goods as g', 'g.id', '=', 'gt.goods_id')
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
                ->where('o.id', '=', $data['id'])
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
