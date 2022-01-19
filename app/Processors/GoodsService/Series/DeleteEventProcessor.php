<?php

namespace App\Processors\GoodsService\Series;

use App\Models\Eloquent\Series;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param Series $model
     */
    public function __construct(Series $model)
    {
        $this->model = $model;
    }
}
