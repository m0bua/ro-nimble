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
            'is_chosen',
            'products_quantity',
        ];
    }
}
