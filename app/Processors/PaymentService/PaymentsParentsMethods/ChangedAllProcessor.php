<?php

namespace App\Processors\PaymentService\PaymentsParentsMethods;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PaymentMethod;
use Illuminate\Support\Arr;

class ChangedAllProcessor implements ProcessorInterface
{
    protected PaymentMethod $model;

    /**
     * ChangedAllProcessor constructor.
     * @param PaymentMethod $model
     */
    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $fields = $this->model->getFillable();
        $rawData = (array)$message->getField('fields_data');
        $data = Arr::only($rawData, $fields);

        $this->model->write()->upsert($data, 'id', Arr::except($data, 'id'));

        return Codes::SUCCESS;
    }
}
