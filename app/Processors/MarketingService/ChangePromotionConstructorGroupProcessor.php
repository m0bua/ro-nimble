<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorGroupProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $constructorId = $message->getField('fields_data.promotion_constructor_id');
        $groupId = $message->getField('fields_data.group_id');

        $updated = DB::table('promotion_groups_constructors')
            ->where([
                ['constructor_id', '=', $constructorId],
                ['group_id', '=', $groupId],
            ])
            ->update([
                'needs_index' => 1,
                'needs_migrate' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($updated == 0) {
            DB::table('promotion_groups_constructors')
                ->insert([
                    'constructor_id' => $constructorId,
                    'group_id' => $groupId,
                    'needs_index' => 1,
                    'needs_migrate' => 1,
                ]);
        }

        return Codes::SUCCESS;
    }
}
