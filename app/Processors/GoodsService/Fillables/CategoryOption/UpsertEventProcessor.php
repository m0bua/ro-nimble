<?php

namespace App\Processors\GoodsService\Fillables\CategoryOption;

use App\Models\Eloquent\CategoryOption;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    protected array $compoundKey = [
        'category_id',
        'option_id',
    ];

    /**
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }
}
