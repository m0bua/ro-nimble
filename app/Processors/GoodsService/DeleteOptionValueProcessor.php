<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteOptionValueProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = null;

    protected OptionValue $model;

    /**
     * DeleteOptionValueProcessor constructor.
     * @param OptionValue $model
     */
    public function __construct(OptionValue $model)
    {
        $this->model = $model;
    }
}
