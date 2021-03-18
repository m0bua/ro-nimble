<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangeProducerEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $producerData = (array)$message->getField('data');

        DB::table('producers')
            ->where(['id' => $producerData['id']])
            ->update([
                'id' => $producerData['id'] ?? null,
                'ext_id' => $producerData['ext_id'] ?? null,
                'title' => $producerData['title'] ?? null,
                'title_rus' => $producerData['title_rus'] ?? null,
                'name' => $producerData['name'] ?? null,
                'text' => $producerData['text'] ?? null,
                'status' => $producerData['status'] ?? null,
                'show_background' => ($producerData['show_background'] ? 't' : 'f'),
                'show_logo' => ($producerData['show_logo'] ? 't' : 'f'),
                'attachments' => $producerData['attachments'] ?? null,
                'disable_filter_series' => ($producerData['disable_filter_series'] ? 't' : 'f'),
                'order_for_promotion' => $producerData['order_for_promotion'] ?? null,
                'producer_rank' => $producerData['producer_rank'] ?? null,
                'updated_at' => date('Y-m-d H:i:s'),
                'needs_index' => 1
            ]);

        return Codes::SUCCESS;
    }
}
