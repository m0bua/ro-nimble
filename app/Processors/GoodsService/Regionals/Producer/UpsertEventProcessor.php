<?php

namespace App\Processors\GoodsService\Regionals\Producer;

use App\Models\Eloquent\Producer;
use App\Processors\GoodsService\Regionals\RegionalProcessor;

class UpsertEventProcessor extends RegionalProcessor
{
    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
