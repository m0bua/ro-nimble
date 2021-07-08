<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\ChangeOptionEntityProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeOptionEntityProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeOptionEntityProcessor::class;

    public static string $modelNamespace = Option::class;

    public static ?string $translationNamespace = OptionTranslation::class;
}
