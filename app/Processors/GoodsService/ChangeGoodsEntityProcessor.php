<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Exception;
use Illuminate\Support\Facades\DB;

class ChangeGoodsEntityProcessor extends AbstractCore
{
    /**
     * @return int
     * @throws Exception
     */
    public function doJob()
    {
        $goodsData = (array)$this->message->getField('data');

        DB::table('goods')
            ->updateOrInsert(
                ['id' => $goodsData['id']],
                [
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
                    'needs_index' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            );

        return Processor::CODE_SUCCESS;
    }
}
