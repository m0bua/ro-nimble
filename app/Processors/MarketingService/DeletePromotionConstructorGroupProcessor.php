<?php

namespace App\Processors\MarketingService;

use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeletePromotionConstructorGroupProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $compoundKey = [
        'constructor_id',
        'group_id',
    ];

    protected PromotionGroupConstructor $model;

    /**
     * DeletePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
    {
        $this->model = $model;
    }
}
