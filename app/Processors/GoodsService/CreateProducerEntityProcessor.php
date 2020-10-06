<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;
use ReflectionException;

class CreateProducerEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function doJob()
    {
        $producerData = (array)$this->message->getField('data');

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

        return Processor::CODE_SUCCESS;
    }
}
