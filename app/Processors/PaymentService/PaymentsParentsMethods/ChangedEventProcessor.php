<?php

namespace App\Processors\PaymentService\PaymentsParentsMethods;

use App\Models\Eloquent\PaymentParentMethod;
use App\Processors\PaymentService\PaymentsMethods\ChangedEventProcessor as Processor;

class ChangedEventProcessor extends Processor
{
    /**
     * @param PaymentParentMethod $model
     * @noinspection MagicMethodsValidityInspection
     * @noinspection SenselessMethodDuplicationInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct(PaymentParentMethod $model)
    {
        $this->model = $model;
    }
}
