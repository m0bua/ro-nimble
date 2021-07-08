<?php

namespace Tests\Feature\Processors\GoodsService\Translations;

use App\Models\Eloquent\Producer;
use App\Models\Eloquent\ProducerTranslation;
use App\Processors\GoodsService\Translations\ChangeProducerRoProcessor;
use App\Support\Language;

class ChangeProducerRoProcessorTest extends ChangeTranslationProcessorTestCase
{
    public static string $processorNamespace = ChangeProducerRoProcessor::class;

    public static string $modelNamespace = Producer::class;

    public static string $translationNamespace = ProducerTranslation::class;

    public static string $language = Language::RO;
}
