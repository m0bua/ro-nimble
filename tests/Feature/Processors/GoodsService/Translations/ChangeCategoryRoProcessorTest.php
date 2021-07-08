<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\Translations\ChangeCategoryRoProcessor;
use App\Support\Language;

class ChangeCategoryRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeCategoryRoProcessor::class;

    public static string $modelNamespace = Category::class;

    public static string $translationNamespace = CategoryTranslation::class;

    public static string $language = Language::RO;
}
