<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;

class ChangeGoodsOptionPluralProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $data = (array)$this->message->getField('data');

        DB::table('goods_options_plural')->updateOrInsert(
            [
                'goods_id' => $data['goods_id'],
                'option_id' => $data['option_id'],
                'value_id' => $data['value_id'],
            ],
            [
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );

        DB::table('goods')
            ->where(['id' => $data['goods_id']])
            ->update(['needs_index' => 1]);


        return Processor::CODE_SUCCESS;
    }
}
