<?php

namespace App\Http\Resources\FilterResource\Sliders;


use App\Http\Resources\BaseResource;

class SlidersOptionResource extends BaseResource
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
            'option_type',
            'special_combobox_view',
            'comparable',
            'config',
            [
                'field' => 'config',
                'resource' => [
                    'class' => SliderConfigResource::class,
                    'method' => 'make'
                ],
            ],
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
            'order',
        ];
    }
}
