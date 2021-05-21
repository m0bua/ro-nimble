<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Option;
use Illuminate\Support\Arr;

class ChangeOptionEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Option
     */
    protected Option $model;

    /**
     * ChangeOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model
            ->write()
            ->where('id', $rawData['id'])
            ->update($data);

        return Codes::SUCCESS;
    }
}
