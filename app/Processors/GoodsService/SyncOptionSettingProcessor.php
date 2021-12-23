<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncOptionSettingProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected OptionSetting $model;
    public static array $uniqueBy = ['id'];

    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
