<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('fields_data.id');
        $promotionId = $message->getField('fields_data.promotion_id');
        $giftId = $message->getField('fields_data.gift_id');

        $updated = DB::table('promotion_constructors')
            ->where(['id' => $id])
            ->update([
                'promotion_id' => $promotionId,
                'gift_id' => $giftId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($updated == 0) {
            DB::table('promotion_constructors')
                ->insert([
                    'id' => $id,
                    'promotion_id' => $promotionId,
                    'gift_id' => $giftId,
                ]);
        }

        return Codes::SUCCESS;
    }
}
