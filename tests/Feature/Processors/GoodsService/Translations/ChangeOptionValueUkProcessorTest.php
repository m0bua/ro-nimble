<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionValueUkProcessor;

class ChangeOptionValueUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionValueUkProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static string $translationNamespace = OptionValueTranslation::class;
}
