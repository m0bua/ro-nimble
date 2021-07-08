<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\CreateOptionValueProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateOptionValueProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateOptionValueProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static ?string $translationNamespace = OptionValueTranslation::class;
}
