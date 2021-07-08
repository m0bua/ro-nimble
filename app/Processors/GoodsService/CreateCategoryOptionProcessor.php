<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\CategoryOption;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateCategoryOptionProcessor extends AbstractProcessor
{
    use WithCreate;

    public static ?array $compoundKey = [
        'category_id',
        'option_id',
    ];

    protected CategoryOption $model;

    /**
     * CreateCategoryOptionProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }
}
