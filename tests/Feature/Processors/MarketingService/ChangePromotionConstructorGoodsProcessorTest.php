<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Models\Eloquent\PromotionGoodsConstructor;
use App\Processors\MarketingService\ChangePromotionConstructorGoodsProcessor;
use Tests\Feature\Processors\CreateProcessorTestCase;
use Tests\Feature\Processors\WithUpsert;

class ChangePromotionConstructorGoodsProcessorTest extends CreateProcessorTestCase
{
    use WithUpsert;

    public static string $processorNamespace = ChangePromotionConstructorGoodsProcessor::class;

    public static string $modelNamespace = PromotionGoodsConstructor::class;

    public static bool $hasOwnId = false;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $uniqueColumns = [
        'constructor_id',
        'goods_id',
    ];

    public static array $aliases = [
        'promotion_constructor_id' => 'constructor_id',
    ];
}
