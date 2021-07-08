<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeSeriesEntityProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected Series $model;

    /**
     * ChangeSeriesEntityProcessor constructor.
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
