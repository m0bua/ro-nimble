<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\Translations\CreateOptionUkProcessor;

class CreateOptionUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionUkProcessor::class;

    public static string $modelNamespace = Option::class;

    public static string $translationNamespace = OptionTranslation::class;
}
