<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\Translations\CreateOptionSettingRoProcessor;
use App\Support\Language;

class CreateOptionSettingRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateOptionSettingRoProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static string $translationNamespace = OptionSettingTranslation::class;

    public static string $language = Language::RO;
}
