<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class DeletePromotionConstructorProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        DB::table('promotion_constructors')
            ->where(['id' => $message->getField('fields_data.id')])
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
