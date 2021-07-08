<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class ChangeGoodsUkProcessor extends ChangeAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Goods
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * ChangeGoodsUkProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
