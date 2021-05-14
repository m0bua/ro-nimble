<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryEntity;
use Illuminate\Support\Arr;

class CreateCategoryEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryEntity
     */
    protected CategoryEntity $model;

    /**
     * CreateCategoryEntityProcessor constructor.
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
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model->create($data);

        return Codes::SUCCESS;
    }
}
