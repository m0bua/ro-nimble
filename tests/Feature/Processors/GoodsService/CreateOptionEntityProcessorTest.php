<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\CreateOptionEntityProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateOptionEntityProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateOptionEntityProcessor::class;

    public static string $modelNamespace = Option::class;

    public static ?string $translationNamespace = OptionTranslation::class;
}
