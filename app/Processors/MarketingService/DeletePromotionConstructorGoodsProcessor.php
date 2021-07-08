<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeletePromotionConstructorGoodsProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'constructor_id',
        'goods_id',
    ];

    protected PromotionGoodsConstructor $model;

    /**
     * DeletePromotionConstructorGoodsProcessor constructor.
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(PromotionGoodsConstructor $model)
    {
        $this->model = $model;
    }
}
