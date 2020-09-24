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
        DB::table('promotion_goods_constructors')
            ->updateOrInsert([
                'constructor_id' => $this->message->getField('fields_data.promotion_constructor_id'),
                'goods_id' => $this->message->getField('fields_data.goods_id')
            ], [
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return Processor::CODE_SUCCESS;
    }
}
