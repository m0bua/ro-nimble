<?php

namespace App\Processors\GoodsService\Translations\Producer;

use App\Models\Eloquent\Producer;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
