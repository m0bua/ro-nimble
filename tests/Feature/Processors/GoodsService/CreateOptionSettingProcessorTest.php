<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\CreateOptionSettingProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateOptionSettingProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateOptionSettingProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static ?string $translationNamespace = OptionSettingTranslation::class;
}
