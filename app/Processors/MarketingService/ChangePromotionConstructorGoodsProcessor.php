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
        DB::table('promotion_goods_constructors')
            ->updateOrInsert([
                'constructor_id' => $constructorId,
                'goods_id' => $this->message->getField('fields_data.goods_id')
            ], [
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        DB::table('promotion_constructors')
            ->where(['id' => $constructorId])
            ->update(['needs_index' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
