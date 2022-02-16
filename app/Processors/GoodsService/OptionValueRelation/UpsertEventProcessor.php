<?php

namespace App\Processors\GoodsService\OptionValueRelation;

use App\Models\Eloquent\OptionValueRelation;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * @param OptionValueRelation $model
     */
    public function __construct(OptionValueRelation $model)
    {
        $this->model = $model;
    }
}
