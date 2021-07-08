<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\CategoryOption;
use App\Models\Eloquent\CategoryOptionTranslation;
use App\Processors\GoodsService\CreateCategoryOptionProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateCategoryOptionProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateCategoryOptionProcessor::class;

    public static string $modelNamespace = CategoryOption::class;

    public static ?string $translationNamespace = CategoryOptionTranslation::class;

    public static bool $hasOwnId = false;
}
