<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionSettingRoProcessor;
use App\Support\Language;

class ChangeOptionSettingRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionSettingRoProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static string $translationNamespace = OptionSettingTranslation::class;

    public static string $language = Language::RO;
}
