<?php

namespace App\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Support\Language;
use Illuminate\Database\Eloquent\Model;

class SyncGoodsRoProcessor extends SyncAbstractTranslationProcessor
{
    /**
     * @inheritDoc
     * @var Goods
     */
    protected Model $model;

    public static ?string $language = Language::RO;

    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        parent::__construct();

        $this->model = $model;
    }
}
