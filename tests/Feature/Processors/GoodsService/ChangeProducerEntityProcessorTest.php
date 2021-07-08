<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\ChangeProducerEntityProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeProducerEntityProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeProducerEntityProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static ?string $translationNamespace = ProducerTranslation::class;

    public static array $aliases = [
        'rank' => 'producer_rank',
    ];
}
