<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\CategoryOption;
use App\Models\Eloquent\CategoryOptionTranslation;
use App\Processors\GoodsService\Translations\CreateCategoryOptionRoProcessor;
use App\Support\Language;

class CreateCategoryOptionRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateCategoryOptionRoProcessor::class;

    public static string $modelNamespace = CategoryOption::class;

    public static string $translationNamespace = CategoryOptionTranslation::class;

    public static bool $hasOwnId = false;

    public static ?array $compoundKey = [
        'category_id',
        'option_id',
    ];

    public static string $language = Language::RO;
}
