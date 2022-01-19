<?php

namespace App\Processors\GoodsService\Option;

use App\Models\Eloquent\Option;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
