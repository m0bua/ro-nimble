<?php

namespace App\Processors\GoodsService\Translations\Series;

use App\Models\Eloquent\Series;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
