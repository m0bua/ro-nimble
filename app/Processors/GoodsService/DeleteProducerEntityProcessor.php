<?php

namespace App\Processors\GoodsService;

use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;

class DeleteProducerEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $producerId = $this->message->getField('id');

        DB::table('producers')
            ->where(['id' => $producerId])
            ->delete();

        return Processor::CODE_SUCCESS;
    }
}
