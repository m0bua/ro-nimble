<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class CreateGoodsUkProcessor extends CreateAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Goods
     */
    protected Model $model;

    public static ?string $language = Language::UK;

    /**
     * CreateGoodsUkProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
