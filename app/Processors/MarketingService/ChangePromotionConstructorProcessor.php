<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangePromotionConstructorProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy = [
        'id',
    ];

    public static ?string $dataRoot = 'fields_data';

    protected PromotionConstructor $model;

    /**
     * ChangePromotionConstructorProcessor constructor.
     * @param PromotionConstructor $model
     */
    public function __construct(PromotionConstructor $model)
    {
        $this->model = $model;
    }
}
