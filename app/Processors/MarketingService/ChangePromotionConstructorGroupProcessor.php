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
        $constructorId = $this->message->getField('fields_data.promotion_constructor_id');
        $groupId = $this->message->getField('fields_data.group_id');

        $updated = DB::table('promotion_groups_constructors')
            ->where([
                ['constructor_id', '=', $constructorId],
                ['group_id', '=', $groupId],
            ])
            ->update([
                'needs_index' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($updated == 0) {
            DB::table('promotion_goods_constructors')
                ->insert([
                    'constructor_id' => $constructorId,
                    'group_id' => $groupId,
                    'needs_index' => 1,
                ]);
        }

        DB::table('promotion_constructors')
            ->where(['id' => $constructorId])
            ->update(['needs_index' => 1]);

        return Processor::CODE_SUCCESS;
    }
}
