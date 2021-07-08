<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\Translations\CreateOptionValueUkProcessor;

class CreateOptionValueUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionValueUkProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static string $translationNamespace = OptionValueTranslation::class;
}
