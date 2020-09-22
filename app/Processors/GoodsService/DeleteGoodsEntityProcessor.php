<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;

class DeleteGoodsEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $goodsId = $this->message->getField('id');

        DB::table('goods')
            ->where(['id' => $goodsId])
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
