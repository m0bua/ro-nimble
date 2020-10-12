<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class DeleteOptionEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $optionId = $message->getField('id');

        DB::table('options')
            ->where(['id' => $optionId])
            ->delete();

        return Codes::SUCCESS;
    }
}
