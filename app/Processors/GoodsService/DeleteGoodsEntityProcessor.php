<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class DeleteGoodsEntityProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $goodsId = $message->getField('id');

        DB::table('goods')
            ->where(['id' => $goodsId])
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
