<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangePromotionConstructorGroupProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy = [
        'constructor_id',
        'group_id',
    ];

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'constructor_id',
        'group_id',
    ];

    protected static array $aliases = [
        'promotion_constructor_id' => 'constructor_id',
    ];

    protected PromotionGroupConstructor $model;

    /**
     * ChangePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
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
