<?php

namespace App\Processors\GoodsService\Fillables\Producer;

use App\Models\Eloquent\Producer;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
