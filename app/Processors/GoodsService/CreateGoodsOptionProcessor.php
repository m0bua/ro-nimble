<?php


namespace App\Processors\GoodsService;


use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use Illuminate\Support\Facades\DB;

class CreateGoodsOptionProcessor implements ProcessorInterface
{
    public function processMessage(MessageInterface $message): int
    {
        $data = (array)$message->getField('data');
        DB::table('goods_options')->insert(
            [
                'goods_id' => $data['goods_id'],
                'option_id' => $data['option_id'],
                'type' => $data['type'],
                'value' => $data['value']
            ]
        );

        DB::table('goods')
            ->where(['id' => $data['goods_id']])
            ->update(['needs_index' => 1]);

        return Codes::SUCCESS;
    }
}
