<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionSetting;
use App\Models\Eloquent\OptionSettingTranslation;
use App\Processors\GoodsService\DeleteOptionSettingProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteOptionSettingProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteOptionSettingProcessor::class;

    public static string $modelNamespace = OptionSetting::class;

    public static ?string $translationNamespace = OptionSettingTranslation::class;

    public static ?string $dataRoot = null;
}
