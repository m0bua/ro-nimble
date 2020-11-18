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
        $changed = (array)$message->getField('changed');

        $intersect = array_intersect($updateFields, $changed);

        if (!empty($intersect)) {
            $updateData = [];
            foreach ($intersect as $field) {
                $updateData[$field] = $goodsData[$field];
            }

            DB::table('goods')
                ->where(['id' => $goodsData['id']])
                ->update($updateData);
//                ->update([
//                    'title' => $goodsData['title'],
//                    'name' => $goodsData['name'],
//                    'category_id' => $goodsData['category_id'],
//                    'mpath' => $goodsData['mpath'],
//                    'price' => $goodsData['price'],
//                    'rank' => $goodsData['rank'],
//                    'sell_status' => $goodsData['sell_status'],
//                    'group_id' => $goodsData['group_id'],
//                    'is_group_primary' => $goodsData['is_group_primary'],
//                    'status_inherited' => $goodsData['status_inherited'],
//                    'order' => $goodsData['order'],
//                    'series_id' => $goodsData['series_id'],
//                    'state' => $goodsData['state'],
//                    'producer_id' => $goodsData['producer_id'],
//                    'seller_id' => $goodsData['seller_id'],
//                    'needs_index' => 1,
//                    'updated_at' => date('Y-m-d H:i:s'),
//                ]);
        }

        return Codes::SUCCESS;
    }
}
