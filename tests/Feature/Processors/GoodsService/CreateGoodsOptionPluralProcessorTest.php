<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\GoodsService\CreateGoodsOptionPluralProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateGoodsOptionPluralProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateGoodsOptionPluralProcessor::class;

    public static string $modelNamespace = GoodsOptionPlural::class;

    public static bool $hasOwnId = false;
}
