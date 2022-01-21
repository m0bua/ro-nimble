<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\IndexGoods;
use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeletePromotionConstructorGoodsProcessor extends AbstractProcessor
{
    use WithDelete;

    const CONSTRUCTOR_ID_KEY = 'constructor_id';

    public static bool $softDelete = false;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        'goods_id',
    ];

    protected static array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
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

    protected function afterProcess(): void
    {
        IndexGoods::query()->insertOrIgnore(['id' => $this->data['goods_id']]);
    }
}
