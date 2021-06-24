<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Producer;

class DeleteProducerEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Producer
     */
    protected Producer $model;

    /**
     * DeleteProducerEntityProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('id');

        $this->model
            ->whereId($id)
            ->update([
                'is_deleted' => 1,
            ]);

        return Codes::SUCCESS;
    }
}
