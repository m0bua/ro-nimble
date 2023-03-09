<?php

namespace App\Processors\GoodsService\Fillables\OptionSetting;

use App\Models\Eloquent\OptionSetting;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
