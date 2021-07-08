<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\ChangeOptionValueProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeOptionValueProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionValueProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static ?string $translationNamespace = OptionValueTranslation::class;
}
