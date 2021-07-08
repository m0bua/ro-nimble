<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\CategoryOption;
use App\Models\Eloquent\CategoryOptionTranslation;
use App\Processors\GoodsService\ChangeCategoryOptionProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeCategoryOptionProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeCategoryOptionProcessor::class;

    public static string $modelNamespace = CategoryOption::class;

    public static ?string $translationNamespace = CategoryOptionTranslation::class;

    public static bool $hasOwnId = false;

    public static ?array $uniqueColumns = [
        'category_id',
        'option_id',
    ];
}
