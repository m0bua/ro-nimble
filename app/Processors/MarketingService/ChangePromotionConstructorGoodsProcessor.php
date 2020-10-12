<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorGoodsProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $constructorId = $message->getField('fields_data.promotion_constructor_id');
        $goodsId = $message->getField('fields_data.goods_id');

        $updated = DB::table('promotion_goods_constructors')
            ->where([
                ['constructor_id', '=', $constructorId],
                ['goods_id', '=', $goodsId]
            ])
            ->update([
                'needs_index' => 1,
                'needs_migrate' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($updated == 0) {
            DB::table('promotion_goods_constructors')
                ->insert([
                    'constructor_id' => $constructorId,
                    'goods_id' => $goodsId,
                    'needs_index' => 1,
                    'needs_migrate' => 1,
                ]);
        }

        return Codes::SUCCESS;
    }
}
