<?php

namespace App\Processors\GoodsService\Goods;

use App\Models\Eloquent\Goods;
use App\Processors\DeleteProcessor;

class DeleteEventProcessor extends DeleteProcessor
{
    protected bool $softDelete = true;

    protected string $dataRoot;

    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }
}
