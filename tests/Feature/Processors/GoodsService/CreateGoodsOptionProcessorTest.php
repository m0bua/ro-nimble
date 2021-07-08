<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\GoodsOption;
use App\Processors\GoodsService\CreateGoodsOptionProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateGoodsOptionProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateGoodsOptionProcessor::class;

    public static string $modelNamespace = GoodsOption::class;

    public static bool $hasOwnId = false;
}
