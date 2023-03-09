<?php

namespace App\Processors\GoodsService\Regionals\Series;

use App\Models\Eloquent\Series;
use App\Processors\GoodsService\Regionals\RegionalProcessor;

class UpsertEventProcessor extends RegionalProcessor
{
    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
