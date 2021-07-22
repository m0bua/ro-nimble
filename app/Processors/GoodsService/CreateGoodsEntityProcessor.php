<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use Illuminate\Support\Arr;

class CreateGoodsEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Goods
     */
    protected Goods $model;

    /**
     * CreateGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $this->model->getFillable());

        $this->model->insertOrIgnore($data);

        return Codes::SUCCESS;
    }
}
