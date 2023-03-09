<?php

namespace App\Processors\GoodsService\Fillables\Option;

use App\Models\Eloquent\Option;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
