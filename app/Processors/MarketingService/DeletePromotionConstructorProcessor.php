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

    public static bool $softDelete = false;

    public static ?string $dataRoot = 'fields_data';
}
