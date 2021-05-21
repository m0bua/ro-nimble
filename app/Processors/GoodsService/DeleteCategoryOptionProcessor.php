<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryOption;
use Exception;

class DeleteCategoryOptionProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryOption
     */
    protected CategoryOption $model;

    /**
     * DeleteCategoryOptionProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
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

        $this->model->write()->whereId($id)->update([
            'is_deleted' => true,
        ]);

        return Codes::SUCCESS;
    }
}
