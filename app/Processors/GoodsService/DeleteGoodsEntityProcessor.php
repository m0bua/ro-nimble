<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteGoodsEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = null;

    protected Goods $model;

    /**
     * DeleteGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }
}
