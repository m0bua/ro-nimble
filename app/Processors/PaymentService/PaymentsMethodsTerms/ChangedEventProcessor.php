<?php

namespace App\Processors\PaymentService\PaymentsMethodsTerms;

use App\Processors\UpsertProcessor;
use App\Models\Eloquent\PaymentMethodsTerm;

class ChangedEventProcessor extends UpsertProcessor
{
    protected array $compoundKey = [
           'id',
    ];

    protected string $dataRoot = 'fields_data';

    /**
     * ChangedEventProcessor constructor.
     * @param PaymentMethodsTerm $model
     */
    public function __construct(PaymentMethodsTerm $model)
    {
        $this->model = $model;
    }
}
