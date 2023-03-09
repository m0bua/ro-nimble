<?php

namespace App\Processors\GoodsService\Fillables\Category;

use App\Models\Eloquent\Category;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
