<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\CategoryOption;
use Illuminate\Support\Arr;

class ChangeCategoryOptionProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var CategoryOption
     */
    protected CategoryOption $model;

    /**
     * ChangeCategoryOptionProcessor constructor.
     * @param CategoryOption $model
     */
    public function __construct(CategoryOption $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('data.id');

        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model->write()->whereId($id)->update($data);

        return Codes::SUCCESS;
    }
}
