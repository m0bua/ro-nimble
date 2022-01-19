<?php

namespace App\Processors\GoodsService\Category;

use App\Models\Eloquent\Category;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected bool $softDelete = true;

    protected string $dataRoot;

    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
