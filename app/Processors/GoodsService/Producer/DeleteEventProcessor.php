<?php

namespace App\Processors\GoodsService\Producer;

use App\Models\Eloquent\Producer;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected bool $softDelete = true;

    protected string $dataRoot;

    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
