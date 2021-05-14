<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryEntity;

class DeleteCategoryEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryEntity
     */
    protected CategoryEntity $model;

    /**
     * DeleteCategoryEntityProcessor constructor.
     * @param CategoryEntity $model
     */
    public function __construct(CategoryEntity $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('id');

        $this->model->whereId($id)->delete();

        return Codes::SUCCESS;
    }
}
