<?php

namespace App\Http\Resources\GoodsResource;

use App\Http\Resources\BaseResource;

class GoodsResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'ids',
            'ids_count',
            'shown_page',
            'goods_limit',
            'total_pages',
        ];
    }
}
