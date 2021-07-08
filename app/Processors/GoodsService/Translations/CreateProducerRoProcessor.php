<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use Illuminate\Database\Eloquent\Model;

class CreateProducerRoProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Producer
     */
    protected Model $model;

    /**
     * CreateProducerRoProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
