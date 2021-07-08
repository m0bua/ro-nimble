<?php

namespace Tests\Feature\Processors\MarketingService;

use App\Models\Eloquent\PromotionConstructor;
use App\Processors\MarketingService\DeletePromotionConstructorProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeletePromotionConstructorProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeletePromotionConstructorProcessor::class;

    public static string $modelNamespace = PromotionConstructor::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = 'fields_data';
}
