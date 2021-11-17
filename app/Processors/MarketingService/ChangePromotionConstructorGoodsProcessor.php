<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangePromotionConstructorGoodsProcessor extends AbstractProcessor
{
    use WithUpsert;

    const CONSTRUCTOR_ID_KEY = 'constructor_id';
    const GOODS_ID_KEY = 'goods_id';

    public static array $uniqueBy = [
        self::CONSTRUCTOR_ID_KEY,
        self::GOODS_ID_KEY,
    ];

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        self::CONSTRUCTOR_ID_KEY,
        self::GOODS_ID_KEY,
    ];

    protected static array $aliases = [
        'promotion_constructor_id' => self::CONSTRUCTOR_ID_KEY,
    ];

    protected PromotionGoodsConstructor $model;

    /**
     * ChangePromotionConstructorGoodsProcessor constructor.
     * @param PromotionGoodsConstructor $model
     */
    public function __construct(PromotionGoodsConstructor $model)
    {
        $this->model = $model;
    }

    /**
     * Update or create entity
     *
     * @param array<string>|string $uniqueBy
     * @param array<string>|null $update
     * @return bool
     */
    protected function upsertModel($uniqueBy, ?array $update = null): bool
    {
        $data = $this->prepareData();
        $data['needs_index'] = 1;
        $data['needs_migrate'] = 1;
        $data['is_deleted'] = 0;

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }
}
