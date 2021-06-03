<?php

namespace App\Http\Resources;

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
            'is_chosen',
            'disabled',
            'is_rank',
            'option_value_title',
            'products_quantity',
            'order',
        ];
    }
}
