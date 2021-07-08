<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\Translations\ChangeProducerUkProcessor;

class ChangeProducerUkProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeProducerUkProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static string $translationNamespace = ProducerTranslation::class;
}
