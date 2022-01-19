<?php

namespace App\Processors\GoodsService\OptionValue;

use App\Models\Eloquent\OptionValue;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
