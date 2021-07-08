<?php

namespace App\Processors\PaymentService\PaymentsMethods;

use App\Models\Eloquent\PaymentMethod;
use App\Processors\AbstractProcessor;
use App\Processors\Traits\WithUpsert;

class ChangedEventProcessor extends AbstractProcessor
{
    use WithUpsert;

    public static array $uniqueBy = [
        'id',
    ];

    public static ?string $dataRoot = 'fields_data';

    protected PaymentMethod $model;

    /**
     * ChangedEventProcessor constructor.
     * @param PaymentMethod $model
     */
    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }
}
