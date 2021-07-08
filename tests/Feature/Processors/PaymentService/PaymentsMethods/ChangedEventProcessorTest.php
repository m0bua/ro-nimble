<?php

namespace Tests\Feature\Processors\PaymentService\PaymentsMethods;

use App\Models\Eloquent\PaymentMethod;
use App\Processors\PaymentService\PaymentsMethods\ChangedEventProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;
use Tests\Feature\Processors\WithUpsert;

class ChangedEventProcessorTest extends ChangeProcessorTestCase
{
    use WithUpsert;

    public static string $processorNamespace = ChangedEventProcessor::class;

    public static string $modelNamespace = PaymentMethod::class;

    public static ?string $dataRoot = 'fields_data';
}
