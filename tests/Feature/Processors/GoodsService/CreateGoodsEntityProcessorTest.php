<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\CreateGoodsEntityProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;

class CreateGoodsEntityProcessorTest extends CreateProcessorTestCase
{
    public static string $processorNamespace = CreateGoodsEntityProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static ?string $translationNamespace = GoodsTranslation::class;
}
