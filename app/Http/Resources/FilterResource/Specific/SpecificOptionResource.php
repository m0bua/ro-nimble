<?php

namespace App\Http\Resources\FilterResource\Specific;

use App\Http\Resources\BaseResource;

class SpecificOptionResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            [
                'field' => 'section_id',
                'resource' => [
                    'class' => SectionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'sellers',
                'resource' => [
                    'class' => CustomOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'categories',
                'resource' => [
                    'class' => CustomOptionWithoutTranslateResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'price',
                'resource' => [
                    'class' => PriceResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'producer',
                'resource' => [
                    'class' => ProducerResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'states',
                'resource' => [
                    'class' => CustomOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'goods_with_promotions',
                'resource' => [
                    'class' => CustomOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'sell_statuses',
                'resource' => [
                    'class' => CustomOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'with_bonus',
                'resource' => [
                    'class' => CustomOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'series',
                'resource' => [
                    'class' => CustomOptionWithoutTranslateResource::class,
                    'method' => 'make'
                ]
            ],
        ];
    }
}
