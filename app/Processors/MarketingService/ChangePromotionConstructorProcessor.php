<?php

namespace App\Processors\MarketingService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Exception;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        $id = $this->message->getField('fields_data.id');
        $promotionId = $this->message->getField('fields_data.promotion_id');
        $giftId = $this->message->getField('fields_data.gift_id');

        $updated = DB::table('promotion_constructors')
            ->where(['id' => $id])
            ->update([
                'promotion_id' => $promotionId,
                'gift_id' => $giftId,
                'needs_index' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        
        if ($updated == 0) {
            DB::table('promotion_constructors')
                ->insert([
                    'id' => $id,
                    'promotion_id' => $promotionId,
                    'gift_id' => $giftId,
                    'needs_index' => 1,
                ]);
        }

        return Processor::CODE_SUCCESS;
    }
}
