<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\GoodsService\ChangeSeriesEntityProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeSeriesEntityProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeSeriesEntityProcessor::class;

    public static string $modelNamespace = Series::class;
}
