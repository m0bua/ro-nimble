<?php

namespace App\Processors\GoodsService\Fillables\Series;

use App\Models\Eloquent\Series;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
