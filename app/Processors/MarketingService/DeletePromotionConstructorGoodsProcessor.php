<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class DeletePromotionConstructorGoodsProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        DB::table('promotion_goods_constructors')
            ->where([
                ['constructor_id', '=', $message->getField('fields_data.promotion_constructor_id')],
                ['goods_id', '=', $message->getField('fields_data.goods_id')]
            ])
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
