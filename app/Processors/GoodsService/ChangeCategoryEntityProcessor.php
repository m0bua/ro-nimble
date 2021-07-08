<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeCategoryEntityProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected Category $model;

    /**
     * ChangeCategoryEntityProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
