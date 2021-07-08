<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\Translations\ChangeCategoryUkProcessor;

class ChangeCategoryUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeCategoryUkProcessor::class;

    public static string $modelNamespace = Category::class;

    public static string $translationNamespace = CategoryTranslation::class;
}
