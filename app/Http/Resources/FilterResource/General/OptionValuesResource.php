<?php

namespace App\Http\Resources\FilterResource\General;

use App\Http\Resources\BaseResource;

class OptionValuesResource extends BaseResource
{
    /**
     * @return string[]
     */
    public function getResourceFields(): array
    {
        return [
            'option_value_id',
            'option_value_name',
            'option_value_title',
//            'title_genetive',
//            'title_accusative',
//            'title_prepositional',
            'color_hash',
            'is_chosen',
            'products_quantity',
            'order',
            'is_value_show',
        ];
    }
}
