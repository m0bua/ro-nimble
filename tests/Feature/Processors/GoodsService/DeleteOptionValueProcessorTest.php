<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\OptionValue;
use App\Models\Eloquent\OptionValueTranslation;
use App\Processors\GoodsService\DeleteOptionValueProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteOptionValueProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteOptionValueProcessor::class;

    public static string $modelNamespace = OptionValue::class;

    public static ?string $translationNamespace = OptionValueTranslation::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = null;
}
