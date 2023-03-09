<?php

namespace App\Processors\GoodsService\Regionals\Option;

use App\Models\Eloquent\Option;
use App\Processors\GoodsService\Regionals\RegionalProcessor;

class UpsertEventProcessor extends RegionalProcessor
{
    /**
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
