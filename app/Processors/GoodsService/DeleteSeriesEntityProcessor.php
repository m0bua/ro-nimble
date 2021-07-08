<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteSeriesEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static ?string $dataRoot = null;

    protected Series $model;

    /**
     * DeleteSeriesEntityProcessor constructor.
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
