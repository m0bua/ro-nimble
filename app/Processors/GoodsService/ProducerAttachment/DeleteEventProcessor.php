<?php

namespace App\Processors\GoodsService\ProducerAttachment;

use App\Processors\DeleteProcessor;
use App\Models\Eloquent\ProducersAttachment;

class DeleteEventProcessor extends DeleteProcessor
{
    protected string $dataRoot;

    /**
     * @param ProducersAttachment $model
     */
    public function __construct(ProducersAttachment $model)
    {
        $this->model = $model;
    }
}
