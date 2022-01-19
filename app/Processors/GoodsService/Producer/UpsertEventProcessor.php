<?php

namespace App\Processors\GoodsService\Producer;

use App\Models\Eloquent\Producer;
use App\Processors\UpsertProcessor;

class UpsertEventProcessor extends UpsertProcessor
{
    protected array $aliases = [
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
