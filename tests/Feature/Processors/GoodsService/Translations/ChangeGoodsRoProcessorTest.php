<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\Translations\ChangeGoodsRoProcessor;
use App\Support\Language;

class ChangeGoodsRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeGoodsRoProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static string $translationNamespace = GoodsTranslation::class;

    public static string $language = Language::RO;
}
