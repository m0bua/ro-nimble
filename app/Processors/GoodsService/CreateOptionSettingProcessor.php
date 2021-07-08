<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateOptionSettingProcessor extends AbstractProcessor
{
    use WithCreate;

    protected OptionSetting $model;

    /**
     * CreateOptionSettingProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
