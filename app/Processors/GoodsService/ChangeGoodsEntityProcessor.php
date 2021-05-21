<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use Illuminate\Support\Arr;

class ChangeGoodsEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Goods
     */
    protected Goods $model;

    /**
     * ChangeGoodsEntityProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);
        $data['needs_index'] = 1;

        $this->model->write()->whereId($rawData['id'])->update($data);

        return Codes::SUCCESS;
    }
}
