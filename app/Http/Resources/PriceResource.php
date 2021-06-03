<?php

namespace App\Http\Resources;

class PriceResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'option_name',
            'option_id',
            'special_combobox_view',
            'option_title',
            'option_type',
            'range_values',
            'chosen_values',
            'order'
        ];
    }
}
