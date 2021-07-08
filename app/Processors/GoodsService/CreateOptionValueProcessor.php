<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateOptionValueProcessor extends AbstractProcessor
{
    use WithCreate;

    protected OptionValue $model;

    /**
     * CreateOptionValueProcessor constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
