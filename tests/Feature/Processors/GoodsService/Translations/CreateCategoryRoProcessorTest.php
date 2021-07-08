<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\Translations\CreateCategoryRoProcessor;
use App\Support\Language;

class CreateCategoryRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateCategoryRoProcessor::class;

    public static string $modelNamespace = Category::class;

    public static string $translationNamespace = CategoryTranslation::class;

    public static string $language = Language::RO;
}
