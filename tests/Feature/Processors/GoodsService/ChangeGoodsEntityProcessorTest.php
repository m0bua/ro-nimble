<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\ChangeGoodsEntityProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeGoodsEntityProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeGoodsEntityProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static ?string $translationNamespace = GoodsTranslation::class;
}
