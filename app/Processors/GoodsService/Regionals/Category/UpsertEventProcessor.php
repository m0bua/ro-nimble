<?php

namespace App\Processors\GoodsService\Regionals\Category;

use App\Models\Eloquent\Category;
use App\Processors\GoodsService\Regionals\RegionalProcessor;

class UpsertEventProcessor extends RegionalProcessor
{
    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
