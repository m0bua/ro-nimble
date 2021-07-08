<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\CategoryOption;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeCategoryOptionProcessor extends AbstractProcessor
{
    use WithUpdate;

    public static ?array $compoundKey = [
        'category_id',
        'option_id',
    ];

    protected CategoryOption $model;

    /**
     * ChangeCategoryOptionProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }
}
