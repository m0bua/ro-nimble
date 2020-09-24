<?php

namespace App\Processors\MarketingService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsManyModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;
use Illuminate\Support\Facades\DB;

class ChangePromotionConstructorGroupProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        DB::table('promotion_groups_constructors')
            ->updateOrInsert([
                'constructor_id' => $this->message->getField('fields_data.promotion_constructor_id'),
                'group_id' => $this->message->getField('fields_data.group_id')
            ], [
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return Processor::CODE_SUCCESS;
    }
}
