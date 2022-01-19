<?php

namespace App\Processors\GoodsService\OptionSetting;

use App\Models\Eloquent\OptionSetting;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param OptionSetting $model
     */
    public function __construct(OptionSetting $model)
    {
        $this->model = $model;
    }
}
