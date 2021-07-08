<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\ChangeOptionSettingProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeOptionSettingProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionSettingProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static ?string $translationNamespace = OptionSettingTranslation::class;
}
