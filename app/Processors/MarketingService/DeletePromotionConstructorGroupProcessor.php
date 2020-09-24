<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;
use Illuminate\Support\Facades\DB;

class DeletePromotionConstructorGroupProcessor extends AbstractCore
{

    /**
     * @return mixed|void
     * @throws Exception
     */
    public function doJob()
    {
        DB::table('promotion_groups_constructors')
            ->where([
                ['constructor_id', '=', $this->message->getField('fields_data.promotion_constructor_id')],
                ['group_id', '=', $this->message->getField('fields_data.group_id')]
            ])
            ->update(['is_deleted' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
