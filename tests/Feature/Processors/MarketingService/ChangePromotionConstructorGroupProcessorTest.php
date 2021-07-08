<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Models\Eloquent\PromotionGroupConstructor;
use App\Processors\MarketingService\ChangePromotionConstructorGroupProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;
use Tests\Feature\Processors\WithUpsert;

class ChangePromotionConstructorGroupProcessorTest extends ChangeProcessorTestCase
{
    use WithUpsert;

    public static string $processorNamespace = ChangePromotionConstructorGroupProcessor::class;

    public static string $modelNamespace = PromotionGroupConstructor::class;

    public static bool $hasOwnId = false;

    public static ?string $dataRoot = 'fields_data';

    public static ?array $uniqueColumns = [
        'constructor_id',
        'group_id',
    ];

    public static array $aliases = [
        'promotion_constructor_id' => 'constructor_id',
    ];
}
