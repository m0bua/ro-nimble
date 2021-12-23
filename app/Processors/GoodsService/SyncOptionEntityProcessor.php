<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncOptionEntityProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected Option $model;
    public static array $uniqueBy = ['id'];

    /**
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }
}
