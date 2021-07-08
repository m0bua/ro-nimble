<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\Translations\CreateCategoryUkProcessor;

class CreateCategoryUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateCategoryUkProcessor::class;

    public static string $modelNamespace = Category::class;

    public static string $translationNamespace = CategoryTranslation::class;
}
