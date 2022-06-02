<?php

namespace App\Http\Resources\FilterResource\Specific;

use App\Http\Resources\BaseResource;

class CustomOptionValuesResource extends BaseResource
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
            'is_chosen',
            'products_quantity',
            'order',
        ];
    }
}
