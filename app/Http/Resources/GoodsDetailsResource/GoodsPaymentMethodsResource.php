<?php

namespace App\Http\Resources\GoodsDetailsResource;

use App\Http\Resources\BaseResource;

class GoodsPaymentMethodsResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'order',
            'status',
        ];
    }
}
