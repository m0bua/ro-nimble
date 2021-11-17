<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionConstructor;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeletePromotionConstructorProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = 'fields_data';

    protected PromotionConstructor $model;

    protected PromotionGoodsConstructor $goodsConstructor;

    protected PromotionGroupConstructor $groupConstructor;

    /**
     * DeletePromotionConstructorProcessor constructor.
     * @param PromotionConstructor $model
     * @param PromotionGoodsConstructor $goodsConstructor
     * @param PromotionGroupConstructor $groupConstructor
     */
    public function __construct(PromotionConstructor $model, PromotionGoodsConstructor $goodsConstructor, PromotionGroupConstructor $groupConstructor)
    {
        $this->model = $model;
        $this->goodsConstructor = $goodsConstructor;
        $this->groupConstructor = $groupConstructor;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        // Mark group and goods constructors as deleted too
        $constructorId = $this->data['id'];
        $updatableFields = ['is_deleted' => 1, 'needs_index' => 0, 'needs_migrate' => 0];
        $this->groupConstructor->whereConstructorId($constructorId)->update($updatableFields);
        $this->goodsConstructor->whereConstructorId($constructorId)->update($updatableFields);
    }
}
