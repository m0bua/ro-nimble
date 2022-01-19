<?php

namespace App\Processors\GoodsService\Translations\Goods;

use App\Models\Eloquent\Goods;
use App\Processors\GoodsService\Translations\TranslationProcessor;

class UpsertEventProcessor extends TranslationProcessor
{
    /**
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }
}
