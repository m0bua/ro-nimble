<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateSeriesEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected Series $model;

    /**
     * CreateSeriesEntityProcessor constructor.
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
