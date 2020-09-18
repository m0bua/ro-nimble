<?php

namespace App\Processors\GoodsService;

use App\Models\Elastic\GoodsModel;
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
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
