<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\OptionOneModel;
use App\Processors\AbstractCore;
use App\Helpers\ArrayHelper;
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
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
