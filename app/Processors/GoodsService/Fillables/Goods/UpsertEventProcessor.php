<?php

namespace App\Processors\GoodsService\Fillables\Goods;

use App\Models\Eloquent\Goods;
use App\Processors\FillableProcessor;

class UpsertEventProcessor extends FillableProcessor
{
    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }
}
