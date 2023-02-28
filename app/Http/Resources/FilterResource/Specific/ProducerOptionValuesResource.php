<?php

namespace App\Http\Resources\FilterResource\Specific;

use App\Http\Resources\BaseResource;

class ProducerOptionValuesResource extends BaseResource
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
            'option_value_image',
            'is_chosen',
            'products_quantity',
            'order',
            'is_value_show',
        ];
    }
}
