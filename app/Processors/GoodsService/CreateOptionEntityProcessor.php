<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\OptionOneModel;
use App\Processors\AbstractCore;
use App\Helpers\ArrayHelper;
use App\ValueObjects\Processor;
use Illuminate\Support\Facades\DB;

class CreateOptionEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $option = $this->message->getField('data');

        DB::table('options')->insertOrIgnore(
            [
                'id' => $option['id'],
                'title' => $option['title'],
                'name' => $option['name'],
                'type' => $option['type'],
                'ext_id' => $option['ext_id'],
                'parent_id' => $option['parent_id'],
                'category_id' => $option['category_id'],
                'filtering_type' => $option['filtering_type'],
                'value_separator' => $option['value_separator'],
                'state' => $option['state'],
                'for_record_type' => $option['for_record_type'],
                'order' => $option['order'],
                'record_type' => $option['record_type'],
                'option_record_comparable' => $option['option_record_comparable'],
                'option_record_status' => $option['option_record_status'],
                'affect_group_photo' => $option['affect_group_photo'],
            ]
        );

        return Processor::CODE_SUCCESS;
    }
}
