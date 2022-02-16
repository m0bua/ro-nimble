<?php

namespace App\Processors\GoodsService\OptionValueRelation;

use App\Models\Eloquent\OptionValueRelation;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param OptionValueRelation $model
     */
    public function __construct(OptionValueRelation $model)
    {
        $this->model = $model;
    }
}
