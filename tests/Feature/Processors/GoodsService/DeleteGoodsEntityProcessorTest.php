<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Goods;
use App\Models\Eloquent\GoodsTranslation;
use App\Processors\GoodsService\DeleteGoodsEntityProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteGoodsEntityProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteGoodsEntityProcessor::class;

    public static string $modelNamespace = Goods::class;

    public static ?string $translationNamespace = GoodsTranslation::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = null;
}
