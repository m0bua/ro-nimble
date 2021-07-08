<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithDelete;

class DeleteProducerEntityProcessor extends AbstractProcessor
{
    use WithDelete;

    public static bool $softDelete = true;

    public static ?string $dataRoot = null;

    protected Producer $model;

    /**
     * DeleteProducerEntityProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
