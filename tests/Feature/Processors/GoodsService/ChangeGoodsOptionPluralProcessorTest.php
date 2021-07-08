<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\GoodsOptionPlural;
use App\Processors\GoodsService\ChangeGoodsOptionPluralProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeGoodsOptionPluralProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeGoodsOptionPluralProcessor::class;

    public static string $modelNamespace = GoodsOptionPlural::class;

    public static bool $hasOwnId = false;

    public static ?array $uniqueColumns = [
        'goods_id',
        'option_id',
        'value_id'
    ];
}
