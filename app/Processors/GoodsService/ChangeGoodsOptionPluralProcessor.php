<?php

namespace App\Processors\GoodsService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class ChangeGoodsOptionPluralProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $data = (array)$message->getField('data');

        DB::table('goods_options_plural')
            ->where([
                ['goods_id', '=', $data['goods_id']],
                ['option_id', '=', $data['option_id']],
                ['value_id', '=', $data['value_id']],
            ])
            ->update([
                'needs_index' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        $goods = DB::table('goods')
            ->select(['needs_index'])
            ->where(['id' => $data['goods_id']])
            ->first();

        if (!$goods || $goods->needs_index != 1) {
            DB::table('goods')
                ->where(['id' => $data['goods_id']])
                ->update(['needs_index' => 1]);
        }

        return Codes::SUCCESS;
    }
}
