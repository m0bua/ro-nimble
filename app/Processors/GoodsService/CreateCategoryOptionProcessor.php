<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryOption;
use Illuminate\Support\Arr;

class CreateCategoryOptionProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryOption
     */
    protected CategoryOption $model;

    /**
     * CreateCategoryOptionProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model->create($data);

        return Codes::SUCCESS;
    }
}
