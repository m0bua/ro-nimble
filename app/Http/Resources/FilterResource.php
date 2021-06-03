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
            [
                'field' => 'options',
                'alias' => 'options',
                'inside' => [
                    [
                        'field' => 'general',
                        'resource' => [
                            'class' => GeneralOptionResource::class,
                            'method' => 'collection',
                        ],
                    ],
                    [
                        'field' => 'specific',
                        'resource' => [
                            'class' => SpecificOptionResource::class,
                            'method' => 'make',
                        ],
                    ],
                ],
            ],
            [
                'field' => 'anchors',
                'inside' => [
                    [
                        'field' => 'top',
                        'fill_if_empty' => false,
                        'resource' => [
                            'class' => AnchorResource::class,
                            'method' => 'collection',
                        ],
                    ],
                    [
                        'field' => 'bottom',
                        'fill_if_empty' => false,
                        'resource' => [
                            'class' => AnchorResource::class,
                            'method' => 'collection',
                        ],
                    ],
                ],
            ],
            'chosen',
        ];
    }
}
