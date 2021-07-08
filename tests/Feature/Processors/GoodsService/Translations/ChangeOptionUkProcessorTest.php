<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionUkProcessor;

class ChangeOptionUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionUkProcessor::class;

    public static string $modelNamespace = Option::class;

    public static string $translationNamespace = OptionTranslation::class;
}
