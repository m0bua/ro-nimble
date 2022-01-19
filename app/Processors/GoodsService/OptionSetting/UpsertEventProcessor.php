<?php

namespace App\Processors\GoodsService\OptionSetting;

use App\Models\Eloquent\OptionSetting;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
