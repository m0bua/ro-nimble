<?php

namespace App\Http\Resources;

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
                    'method' => 'collection'
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
