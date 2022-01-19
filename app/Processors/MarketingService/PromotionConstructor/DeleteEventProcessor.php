<?php

namespace App\Processors\MarketingService\PromotionConstructor;

use App\Models\Eloquent\PromotionConstructor;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot = 'fields_data';

    /**
     * @param PromotionConstructor $model
     */
    public function __construct(PromotionConstructor $model)
    {
        $this->model = $model;
    }
}
