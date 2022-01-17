<?php

namespace App\Http\Resources\FilterResource\Specific;


use App\Http\Resources\BaseResource;

class CustomOptionResource extends BaseResource
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
            'title_genetive',
            'title_accusative',
            'title_prepositional',
            'special_combobox_view',
            'comparable',
            'hide_block',
            [
                'field' => 'option_values',
                'resource' => [
                    'class' => CustomOptionValuesResource::class,
                    'method' => 'collection'
                ],
            ],
            'total_found',
            'order',
        ];
    }
}
