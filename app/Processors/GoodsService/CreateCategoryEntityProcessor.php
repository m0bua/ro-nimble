<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;
use App\Processors\Traits\WithUpsert;

class CreateCategoryEntityProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected Category $model;

    public static array $uniqueBy = [
        'id',
    ];

    /**
     * CreateCategoryEntityProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
