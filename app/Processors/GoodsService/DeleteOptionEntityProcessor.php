<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;

class DeleteOptionEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $optionId = $this->message->getField('id');

        DB::table('options')
            ->where(['id' => $optionId])
            ->delete();

        return Processor::CODE_SUCCESS;
    }
}
