<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use Illuminate\Database\Eloquent\Model;

class CreateGoodsRoProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Goods
     */
    protected Model $model;

    /**
     * CreateGoodsRoProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
