<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\GoodsOption;
use App\Models\Eloquent\Option;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteOptionEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = null;

    protected Option $model;

    protected GoodsOption $goodsOption;

    /**
     * DeleteOptionEntityProcessor constructor.
     * @param Option $model
     * @param GoodsOption $goodsOption
     */
    public function __construct(Option $model, GoodsOption $goodsOption)
    {
        $this->model = $model;
        $this->goodsOption = $goodsOption;
    }
}
