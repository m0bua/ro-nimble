<?php

namespace App\Http\Resources;

class OptionResource extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            'option_id',
            'option_values' => [
                'resource' => [
                    'class' => OptionValuesResource::class,
                    'method' => 'collection'
                ],
            ]
        ];
    }
}
