<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Producer;
use Illuminate\Support\Arr;

class CreateProducerEntityProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var Producer
     */
    protected Producer $model;

    /**
     * CreateOptionSettingProcessor constructor.
     * @param Producer $model
     */
    public function __construct(Producer $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $fillable = $this->model->getFillable();
        $rawData = (array)$message->getField('data');
        $data = Arr::only($rawData, $fillable);

        $this->model->create([
            'id' => $data['id'] ?? null,
            'ext_id' => $data['ext_id'] ?? null,
            'title' => $data['title'] ?? null,
            'title_rus' => $data['title_rus'] ?? null,
            'name' => $data['name'] ?? null,
            'text' => $data['text'] ?? null,
            'status' => $data['status'] ?? null,
            'show_background' => $data['show_background'] ? 't' : 'f',
            'show_logo' => $data['show_logo'] ? 't' : 'f',
            'attachments' => $data['attachments'] ?? null,
            'disable_filter_series' => $data['disable_filter_series'] ? 't' : 'f',
            'order_for_promotion' => $data['order_for_promotion'] ?? null,
            'producer_rank' => $data['producer_rank'] ?? null,
            'needs_index' => 1
        ]);

        return Codes::SUCCESS;
    }
}
