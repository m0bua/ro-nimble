<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Producer;
use Illuminate\Support\Arr;

class CreateProducerEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Producer
     */
    protected Producer $model;

    /**
     * CreateOptionSettingProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model->write()->create($data);

        return Codes::SUCCESS;
    }
}
