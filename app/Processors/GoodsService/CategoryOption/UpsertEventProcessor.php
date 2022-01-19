<?php

namespace App\Processors\GoodsService\CategoryOption;

use App\Models\Eloquent\CategoryOption;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
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
