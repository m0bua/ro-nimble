<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeOptionValueProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected OptionValue $model;

    /**
     * ChangeOptionValueProcessor constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
