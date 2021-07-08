<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\Translations\CreateProducerUkProcessor;

class CreateProducerUkProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateProducerUkProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static string $translationNamespace = ProducerTranslation::class;
}
