<?php

namespace App\Http\Resources\FilterResource\General;


use App\Http\Resources\BaseResource;

class GeneralOptionResource extends BaseResource
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
            'hide_block',
            [
                'field' => 'option_values',
                'resource' => [
                    'class' => OptionValuesResource::class,
                    'method' => 'collection'
                ],
            ],
            'total_found',
            'order',
        ];
    }
}
