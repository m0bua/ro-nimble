<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangeGoodsEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $updateFields = [
            'title',
            'name',
            'category_id',
            'mpath',
            'price',
            'rank',
            'sell_status',
            'group_id',
            'is_group_primary',
            'status_inherited',
            'order',
            'series_id',
            'state',
            'producer_id',
            'seller_id',
        ];

        $goodsData = (array)$message->getField('data');
        $updateData = [
            'needs_index' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        foreach ($updateFields as $field) {
            $updateData[$field] = $goodsData[$field];
        }

        DB::table('goods')
            ->where(['id' => $goodsData['id']])
            ->update($updateData);

        return Codes::SUCCESS;
    }
}
