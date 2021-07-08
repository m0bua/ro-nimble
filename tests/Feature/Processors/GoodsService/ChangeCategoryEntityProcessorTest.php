<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\ChangeCategoryEntityProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeCategoryEntityProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeCategoryEntityProcessor::class;

    public static string $modelNamespace = Category::class;

    public static ?string $translationNamespace = CategoryTranslation::class;
}
