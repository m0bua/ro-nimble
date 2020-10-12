<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class CreateProducerEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $producerData = (array)$message->getField('data');

        DB::table('producers')->insertOrIgnore(
            [
                'id' => $producerData['id'],
                'ext_id' => $producerData['ext_id'],
                'title' => $producerData['title'],
                'title_rus' => $producerData['title_rus'],
                'name' => $producerData['name'],
                'text' => $producerData['text'],
                'status' => $producerData['status'],
                'show_background' => ($producerData['show_background'] ? 't' : 'f'),
                'show_logo' => ($producerData['show_logo'] ? 't' : 'f'),
                'attachments' => $producerData['attachments'],
                'disable_filter_series' => ($producerData['disable_filter_series'] ? 't' : 'f'),
                'order_for_promotion' => $producerData['order_for_promotion'],
                'producer_rank' => $producerData['producer_rank'],
                'needs_index' => 1
            ]
        );

        return Codes::SUCCESS;
    }
}
