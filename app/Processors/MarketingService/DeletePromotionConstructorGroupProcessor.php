<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;

use Illuminate\Support\Facades\DB;

class DeletePromotionConstructorGroupProcessor implements ProcessorInterface
{
    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        DB::table('promotion_groups_constructors')
            ->where([
                ['constructor_id', '=', $message->getField('fields_data.promotion_constructor_id')],
                ['group_id', '=', $message->getField('fields_data.group_id')]
            ])
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
