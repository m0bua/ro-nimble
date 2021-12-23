<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\CategoryOption;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncCategoryOptionProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected CategoryOption $model;
    public static array $uniqueBy = ['category_id', 'option_id'];
    public static ?array $compoundKey = ['category_id', 'option_id'];

    /**
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }
}
