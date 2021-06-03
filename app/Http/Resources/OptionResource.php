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
            'general' => [
                'field' => 'general',
                'resource' => [
                    'class' => GeneralOptionResource::class,
                    'method' => 'collection'
                ]
            ],
            'specific' => [
                'field' => 'specific',
                'resource' => [
                    'class' => SpecificOptionResource::class,
                    'method' => 'collection'
                ]
            ],
        ];
    }
}
