<?php

namespace App\Processors\MarketingService\PromotionConstructor;

use App\Interfaces\GoodsBuffer;
use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
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

    protected function deleteModel(): void
    {
        parent::deleteModel();

        $query = PromotionGoodsConstructor::getModel()->query();
        $query = $query->select(['pgc.goods_id'])
            ->from(PromotionGoodsConstructor::getModel()->getTable() . ' as pgc')
            ->where(['constructor_id' => $this->data['id']]);

        foreach ($query->trueCursor($this->maxBatch) as $goods) {
            $this->goodsBuffer->radd($goods->pluck('goods_id')->toArray());
        }
    }
}
