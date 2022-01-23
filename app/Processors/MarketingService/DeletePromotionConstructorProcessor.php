<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeletePromotionConstructorProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = false;

    public static ?string $dataRoot = 'fields_data';

    protected PromotionConstructor $model;

    public function __construct(PromotionConstructor $model)
    {
        $this->model = $model;
    }
}
