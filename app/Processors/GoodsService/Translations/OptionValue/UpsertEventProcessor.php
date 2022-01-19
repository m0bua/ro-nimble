<?php

namespace App\Processors\GoodsService\Translations\OptionValue;

use App\Models\Eloquent\OptionValue;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
