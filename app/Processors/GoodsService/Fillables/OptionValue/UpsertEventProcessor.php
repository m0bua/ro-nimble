<?php

namespace App\Processors\GoodsService\Fillables\OptionValue;

use App\Models\Eloquent\OptionValue;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
