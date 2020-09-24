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
        DB::table('promotion_constructors')
            ->updateOrInsert([
                'id' => $this->message->getField('fields_data.id'),
            ], [
                'promotion_id' => $this->message->getField('fields_data.promotion_id'),
                'gift_id' => $this->message->getField('fields_data.gift_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return Processor::CODE_SUCCESS;
    }
}
