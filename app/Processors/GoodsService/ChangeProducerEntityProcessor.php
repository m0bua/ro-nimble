<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpdate;

class ChangeProducerEntityProcessor extends AbstractProcessor
{
    use WithUpdate;

    protected static array $aliases = [
        'rank' => 'producer_rank',
    ];

    protected Producer $model;

    /**
     * ChangeProducerEntityProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
