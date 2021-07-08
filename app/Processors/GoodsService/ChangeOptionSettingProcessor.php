<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeOptionSettingProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected OptionSetting $model;

    /**
     * ChangeOptionSettingProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
