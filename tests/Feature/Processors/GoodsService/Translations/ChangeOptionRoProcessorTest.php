<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionRoProcessor;
use App\Support\Language;

class ChangeOptionRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionRoProcessor::class;

    public static string $modelNamespace = Option::class;

    public static string $translationNamespace = OptionTranslation::class;

    public static string $language = Language::RO;
}
