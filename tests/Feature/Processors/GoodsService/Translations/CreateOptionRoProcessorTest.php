<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\Translations\CreateOptionRoProcessor;
use App\Support\Language;

class CreateOptionRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionRoProcessor::class;

    public static string $modelNamespace = Option::class;

    public static string $translationNamespace = OptionTranslation::class;

    public static string $language = Language::RO;
}
