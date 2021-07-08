<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateOptionEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected Option $model;

    /**
     * CreateOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
