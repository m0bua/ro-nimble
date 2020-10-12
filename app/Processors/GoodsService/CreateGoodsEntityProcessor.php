<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;
use ReflectionException;

class CreateGoodsEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $goodsData = (array)$message->getField('data');

        DB::table('goods')->insertOrIgnore(
            [
                'id' => $goodsData['id'],
                'title' => $goodsData['title'],
                'name' => $goodsData['name'],
                'category_id' => $goodsData['category_id'],
                'mpath' => $goodsData['mpath'],
                'price' => $goodsData['price'],
                'rank' => $goodsData['rank'],
                'sell_status' => $goodsData['sell_status'],
                'group_id' => $goodsData['group_id'],
                'is_group_primary' => $goodsData['is_group_primary'],
                'status_inherited' => $goodsData['status_inherited'],
                'order' => $goodsData['order'],
                'series_id' => $goodsData['series_id'],
                'state' => $goodsData['state'],
                'producer_id' => $goodsData['producer_id'],
                'seller_id' => $goodsData['seller_id'],
            ]
        );

        return Codes::SUCCESS;
    }
}
