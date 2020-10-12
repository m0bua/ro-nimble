<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangeOptionEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $option = (array)$message->getField('data');

        DB::table('options')
            ->where(['id' => $option['id']])
            ->update([
                'title' => $option['title'],
                'name' => $option['name'],
                'type' => $option['type'],
                'ext_id' => $option['ext_id'],
                'parent_id' => $option['parent_id'],
                'category_id' => $option['category_id'],
                'filtering_type' => $option['filtering_type'],
                'value_separator' => $option['value_separator'],
                'state' => $option['state'],
                'for_record_type' => $option['for_record_type'],
                'order' => $option['order'],
                'record_type' => $option['record_type'],
                'option_record_comparable' => $option['option_record_comparable'],
                'option_record_status' => $option['option_record_status'],
                'affect_group_photo' => ($option['affect_group_photo']) ? 't': 'f',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return Codes::SUCCESS;
    }
}
