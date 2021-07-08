<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use Illuminate\Database\Eloquent\Model;

class ChangeProducerRoProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Producer
     */
    protected Model $model;

    /**
     * ChangeProducerRoProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
