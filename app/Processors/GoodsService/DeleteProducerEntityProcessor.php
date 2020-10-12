<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class DeleteProducerEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $producerId = $message->getField('id');

        DB::table('producers')
            ->where(['id' => $producerId])
            ->delete();

        return Codes::SUCCESS;
    }
}
