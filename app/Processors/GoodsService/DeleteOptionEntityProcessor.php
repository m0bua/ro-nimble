<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Option;

class DeleteOptionEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Option
     */
    protected Option $model;

    /**
     * DeleteOptionEntityProcessor constructor.
     * @param Option $model
     */
    public function __construct(Option $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('id');

        $this->model->whereId($id)->update([
            'is_deleted' => true,
        ]);

        return Codes::SUCCESS;
    }
}
