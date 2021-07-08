<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\GoodsService\CreateSeriesEntityProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateSeriesEntityProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateSeriesEntityProcessor::class;

    public static string $modelNamespace = Series::class;
}
