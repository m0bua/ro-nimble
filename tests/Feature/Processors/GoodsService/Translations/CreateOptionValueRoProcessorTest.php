<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\Translations\CreateOptionValueRoProcessor;
use App\Support\Language;

class CreateOptionValueRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionValueRoProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static string $translationNamespace = OptionValueTranslation::class;

    public static string $language = Language::RO;
}
