<?php

namespace App\Processors\MarketingService\PromotionConstructor;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\Goods;
use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    protected string $dataRoot = 'fields_data';

    /**
     * Maximum count goods for one bulk indexing
     */
    protected int $maxBatch;

    /**
     * @var GoodsBuffer
     */
    protected GoodsBuffer $goodsBuffer;


    /**
     * @param PromotionConstructor $model
     */
    public function __construct(PromotionConstructor $model, GoodsBuffer $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
        $this->maxBatch = env("MAX_INDEXING_BATCH", 100);
    }

    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $record = PromotionConstructor::where(['id' => $data['id']])
            ->where(['promotion_id' => $data['promotion_id']])
            ->first();

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        if (null === $record || !empty(\array_diff(\array_intersect_key($record->toArray(), $data), $data))) {
            $query = PromotionGoodsConstructor::getModel()->query();
            $query = $query->select(['pgc.goods_id'])
                ->from(PromotionGoodsConstructor::getModel()->getTable() . ' as pgc')
                ->where(['constructor_id' => $data['id']]);

            foreach ($query->trueCursor($this->maxBatch) as $goods) {
                $this->goodsBuffer->radd($goods->pluck('goods_id')->toArray());
            }
        }

        return true;
    }
}
