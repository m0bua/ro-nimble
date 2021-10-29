<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
