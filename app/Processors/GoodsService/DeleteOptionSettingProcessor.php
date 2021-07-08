<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteOptionSettingProcessor extends AbstractProcessor
{
    use WithDelete;

    public static ?string $dataRoot = null;

    protected OptionSetting $model;

    /**
     * DeleteOptionSettingProcessor constructor.
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
