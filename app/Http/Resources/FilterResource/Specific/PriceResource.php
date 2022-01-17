<?php

namespace App\Http\Resources\FilterResource\Specific;

use App\Http\Resources\BaseResource;
use App\Http\Resources\FilterResource\Sliders\RangeResource;

class PriceResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'option_id',
            'option_name',
            'option_title',
            'option_type',
            'special_combobox_view',
            'comparable',
            [
                'field' => 'chosen_values',
                'resource' => [
                    'class' => RangeResource::class,
                    'method' => 'make'
                ],
            ],
            [
                'field' => 'range_values',
                'resource' => [
                    'class' => RangeResource::class,
                    'method' => 'make'
                ]
            ],
            'order'
        ];
    }
}
