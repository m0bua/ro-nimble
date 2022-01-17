<?php

namespace App\Http\Resources\GoodsDetailsResource;

use App\Http\Resources\BaseResource;

class GoodsDetailsResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'id',
            [
                'field' => 'bonuses',
                'resource' => [
                    'class' => GoodsBonusesResource::class,
                    'method' => 'make',
                ]
            ],
            [
                'field' => 'payment_methods',
                'resource' => [
                    'class' => GoodsPaymentMethodsResource::class,
                    'method' => 'collection'
                ]
            ],
        ];
    }
}
