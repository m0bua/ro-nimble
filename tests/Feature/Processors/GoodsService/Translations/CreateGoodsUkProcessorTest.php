<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\Translations\CreateGoodsUkProcessor;

class CreateGoodsUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateGoodsUkProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static string $translationNamespace = GoodsTranslation::class;
}
