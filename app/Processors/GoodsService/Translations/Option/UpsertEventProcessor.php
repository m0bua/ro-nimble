<?php

namespace App\Processors\GoodsService\Translations\Option;

use App\Models\Eloquent\Option;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
