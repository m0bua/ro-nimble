<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionValueRoProcessor;
use App\Support\Language;

class ChangeOptionValueRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionValueRoProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static string $translationNamespace = OptionValueTranslation::class;

    public static string $language = Language::RO;
}
