<?php

namespace App\Http\Resources;

class SpecificOptionResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
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
                    'class' => GeneralOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'state',
                'resource' => [
                    'class' => GeneralOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'goods_with_promotions',
                'resource' => [
                    'class' => GeneralOptionResource::class,
                    'method' => 'make'
                ]
            ],
            [
                'field' => 'sell_status',
                'resource' => [
                    'class' => GeneralOptionResource::class,
                    'method' => 'make'
                ]
            ],
        ];
    }
}
