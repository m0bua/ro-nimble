<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\Translations\CreateOptionSettingUkProcessor;

class CreateOptionSettingUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionSettingUkProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static string $translationNamespace = OptionSettingTranslation::class;
}
