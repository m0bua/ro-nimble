<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\Translations\ChangeGoodsUkProcessor;

class ChangeGoodsUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeGoodsUkProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static string $translationNamespace = GoodsTranslation::class;
}
