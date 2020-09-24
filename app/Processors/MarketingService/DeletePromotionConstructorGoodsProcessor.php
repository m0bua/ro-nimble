<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Illuminate\Support\Facades\DB;
use ReflectionException;

class DeletePromotionConstructorGoodsProcessor extends AbstractCore
{
    /**
     * @throws ReflectionException
     */
    public function doJob()
    {
        DB::table('promotion_goods_constructors')
            ->where([
                ['constructor_id', '=', $this->message->getField('fields_data.promotion_constructor_id')],
                ['goods_id', '=', $this->message->getField('fields_data.goods_id')]
            ])
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
