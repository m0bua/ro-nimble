<?php

namespace App\Processors\GoodsService\Translations\Category;

use App\Models\Eloquent\Category;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
