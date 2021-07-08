<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Category;
use App\Models\Eloquent\CategoryTranslation;
use App\Processors\GoodsService\DeleteCategoryEntityProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteCategoryEntityProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteCategoryEntityProcessor::class;

    public static string $modelNamespace = Category::class;

    public static ?string $translationNamespace = CategoryTranslation::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = null;
}
