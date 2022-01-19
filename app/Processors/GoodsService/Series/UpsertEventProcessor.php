<?php

namespace App\Processors\GoodsService\Series;

use App\Models\Eloquent\Series;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
