<?php

namespace App\Processors\GoodsService\Translations\CategoryOption;

use App\Models\Eloquent\CategoryOption;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
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
