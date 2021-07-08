<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use Illuminate\Database\Eloquent\Model;

class ChangeGoodsRoProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Goods
     */
    protected Model $model;

    /**
     * ChangeGoodsRoProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
