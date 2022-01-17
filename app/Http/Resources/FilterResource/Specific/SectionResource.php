<?php

namespace App\Http\Resources\FilterResource\Specific;


use App\Http\Resources\BaseResource;

class SectionResource extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            'option_id',
            'option_name',
            'option_title',
            'current',
            'special_combobox_view',
            'comparable',
            'total_quantity',
            'visible_tree',
            'order',
        ];
    }
}
