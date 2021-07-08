<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateCategoryEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected Category $model;

    /**
     * CreateCategoryEntityProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
