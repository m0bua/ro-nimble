<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Category;

class DeleteCategoryEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Category
     */
    protected Category $model;

    /**
     * DeleteCategoryEntityProcessor constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
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

        $this->model->write()->whereId($id)->delete();

        return Codes::SUCCESS;
    }
}
