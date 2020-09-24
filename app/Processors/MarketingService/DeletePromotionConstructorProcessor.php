<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;
use Illuminate\Support\Facades\DB;

class DeletePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        DB::table('promotion_constructors')
            ->where(['id' => $this->message->getField('fields_data.id')])
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
