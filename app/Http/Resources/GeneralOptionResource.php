<?php

namespace App\Http\Resources;


class GeneralOptionResource extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            'option_title',
            'option_id',
            'option_name',
            'option_type',
            'special_combobox_view',
            'comparable',
            'hide_block',
            [
                'field' => 'option_values',
                'resource' => [
                    'class' => OptionValuesResource::class,
                    'method' => 'collection'
                ],
            ],
            [
                'field' => 'short_list',
                'resource' => [
                    'class' => OptionValuesResource::class,
                    'method' => 'collection'
                ]
            ],
        ];
    }
}
