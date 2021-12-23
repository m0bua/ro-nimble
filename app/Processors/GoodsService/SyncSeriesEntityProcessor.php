<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncSeriesEntityProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected Series $model;
    public static array $uniqueBy = ['id'];

    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
