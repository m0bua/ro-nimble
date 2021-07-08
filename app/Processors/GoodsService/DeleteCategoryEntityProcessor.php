<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteCategoryEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = null;

    protected Category $model;

    /**
     * DeleteCategoryEntityProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
