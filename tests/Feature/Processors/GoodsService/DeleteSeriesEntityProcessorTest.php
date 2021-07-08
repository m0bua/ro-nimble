<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Series;
use App\Processors\GoodsService\DeleteSeriesEntityProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteSeriesEntityProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteSeriesEntityProcessor::class;

    public static string $modelNamespace = Series::class;

    public static ?string $dataRoot = null;
}
