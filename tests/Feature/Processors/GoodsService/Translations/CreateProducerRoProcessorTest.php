<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\Translations\CreateProducerRoProcessor;
use App\Support\Language;

class CreateProducerRoProcessorTest extends CreateTranslationProcessorTestCase
{
    public static string $processorNamespace = CreateProducerRoProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static string $translationNamespace = ProducerTranslation::class;

    public static string $language = Language::RO;
}
