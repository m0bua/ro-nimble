<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\CreateProducerEntityProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateProducerEntityProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateProducerEntityProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static ?string $translationNamespace = ProducerTranslation::class;

    public static array $aliases = [
        'rank' => 'producer_rank',
    ];
}
