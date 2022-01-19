<?php

namespace App\Processors\GoodsService\Translations\OptionSetting;

use App\Models\Eloquent\OptionSetting;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
