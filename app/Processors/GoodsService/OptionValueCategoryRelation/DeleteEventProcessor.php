<?php

namespace App\Processors\GoodsService\OptionValueCategoryRelation;

use App\Models\Eloquent\OptionValueCategoryRelation;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param OptionValueCategoryRelation $model
     */
    public function __construct(OptionValueCategoryRelation $model)
    {
        $this->model = $model;
    }
}
