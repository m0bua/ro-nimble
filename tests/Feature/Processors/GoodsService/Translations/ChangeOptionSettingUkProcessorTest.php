<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\Translations\ChangeOptionSettingUkProcessor;

class ChangeOptionSettingUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionSettingUkProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static string $translationNamespace = OptionSettingTranslation::class;
}
