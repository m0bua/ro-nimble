<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\CreateCategoryEntityProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateCategoryEntityProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateCategoryEntityProcessor::class;

    public static string $modelNamespace = Category::class;

    public static ?string $translationNamespace = CategoryTranslation::class;
}
