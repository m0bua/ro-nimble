<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryEntity;
use Exception;

class DeleteCategoryOptionProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryEntity
     */
    protected CategoryEntity $model;

    /**
     * DeleteCategoryOptionProcessor constructor.
     * @param CategoryEntity $model
     */
    public function __construct(CategoryEntity $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('id');

        $this->model->whereId($id)->update([
            'is_deleted' => true,
        ]);

        return Codes::SUCCESS;
    }
}
