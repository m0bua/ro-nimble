<?php

namespace App\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithCreate;

class CreateProducerEntityProcessor extends AbstractProcessor
{
    use WithCreate;

    protected static array $aliases = [
        'rank' => 'producer_rank',
    ];

    protected Producer $model;

    /**
     * CreateProducerEntityProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }
}
