<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangePromotionConstructorGoodsProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy = [
        'constructor_id',
        'goods_id',
    ];

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'constructor_id',
        'goods_id',
    ];

    protected static array $aliases = [
        'promotion_constructor_id' => 'constructor_id',
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

        $this->model->upsert($data, $uniqueBy, $update);

        // saving translations after creating record if we can do that
        $this->saveTranslations();

        return true;
    }
}
