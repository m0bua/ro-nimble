<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\GoodsOption;
use App\Processors\GoodsService\ChangeGoodsOptionProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeGoodsOptionProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeGoodsOptionProcessor::class;

    public static string $modelNamespace = GoodsOption::class;

    public static bool $hasOwnId = false;

    public static ?array $uniqueColumns = [
        'goods_id',
        'option_id',
        'type',
    ];
}
