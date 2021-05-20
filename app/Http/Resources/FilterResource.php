<?php

namespace App\Http\Resources;

class FilterResource extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            'options' => [
                'resource' => [
                    'class' => OptionResource::class,
                    'method' => 'collection'
                ],
            ],
            'chosen',
        ];
    }
}
