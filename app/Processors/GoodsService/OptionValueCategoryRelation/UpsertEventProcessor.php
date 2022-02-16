<?php

namespace App\Processors\GoodsService\OptionValueCategoryRelation;

use App\Models\Eloquent\OptionValueCategoryRelation;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    /**
     * @param OptionValueCategoryRelation $model
     */
    public function __construct(OptionValueCategoryRelation $model)
    {
        $this->model = $model;
    }
}
