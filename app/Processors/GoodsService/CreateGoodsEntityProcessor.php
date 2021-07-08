<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateGoodsEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected Goods $model;

    /**
     * CreateGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }
}
