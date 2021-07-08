<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\Translations\CreateGoodsRoProcessor;
use App\Support\Language;

class CreateGoodsRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateGoodsRoProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static string $translationNamespace = GoodsTranslation::class;

    public static string $language = Language::RO;
}
