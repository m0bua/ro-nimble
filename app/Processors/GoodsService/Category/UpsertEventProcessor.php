<?php

namespace App\Processors\GoodsService\Category;

use App\Models\Eloquent\Category;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
        'id',
    ];

    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
