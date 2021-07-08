<?php

namespace Tests\Feature\Processors\GoodsService;

use App\Models\Eloquent\Option;
use App\Models\Eloquent\OptionTranslation;
use App\Processors\GoodsService\DeleteOptionEntityProcessor;
use Tests\Feature\Processors\DeleteProcessorTestCase;

class DeleteOptionEntityProcessorTest extends DeleteProcessorTestCase
{
    public static string $processorNamespace = DeleteOptionEntityProcessor::class;

    public static string $modelNamespace = Option::class;

    public static ?string $translationNamespace = OptionTranslation::class;

    public static bool $hasSoftDeletes = true;

    public static ?string $dataRoot = null;
}
