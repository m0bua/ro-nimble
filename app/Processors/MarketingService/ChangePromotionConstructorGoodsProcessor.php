<?php

namespace App\Processors\MarketingService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Exception;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorGoodsProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        $constructorId = $this->message->getField('fields_data.promotion_constructor_id');
        $goodsId = $this->message->getField('fields_data.goods_id');

        $updated = DB::table('promotion_goods_constructors')
            ->where([
                ['constructor_id', '=', $constructorId],
                ['goods_id', '=', $goodsId]
            ])
            ->update([
                'needs_index' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($updated == 0) {
            DB::table('promotion_goods_constructors')
                ->insert([
                    'constructor_id' => $constructorId,
                    'goods_id' => $goodsId,
                    'needs_index' => 1,
                ]);
        }

        DB::table('promotion_constructors')
            ->where(['id' => $constructorId])
            ->update(['needs_index' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
