<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;

class DeleteGoodsEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Goods
     */
    protected Goods $model;

    /**
     * DeleteGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
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
