<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\DeleteProducerEntityProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteProducerEntityProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteProducerEntityProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static ?string $translationNamespace = ProducerTranslation::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = null;
}
