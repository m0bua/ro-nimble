<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeOptionEntityProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected Option $model;

    /**
     * ChangeOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
