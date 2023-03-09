<?php

namespace App\Processors\GoodsService\Regionals\OptionValue;

use App\Models\Eloquent\OptionValue;
use App\Processors\GoodsService\Regionals\RegionalProcessor;

class UpsertEventProcessor extends RegionalProcessor
{
    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
