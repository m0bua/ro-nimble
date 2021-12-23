<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class SyncProducerEntityProcessor extends AbstractProcessor
{
    use WithUpsert;

    protected Producer $model;
    public static array $uniqueBy = ['id'];
    protected static array $aliases = [
        'rank' => 'producer_rank',
    ];

    /**
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
