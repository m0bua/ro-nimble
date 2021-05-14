<?php

namespace App\Http\Resources\V1;

use App\Http\Interfaces\ResourceInterface;
use App\Http\Resources\BaseResource;

class GoodsResource extends BaseResource implements ResourceInterface
{
    public function getResourceFields(): array
    {
        return [
            'ids',
            'ids_count',
            'total_pages',
            'show_next',
            'goods_with_filter',
            'goods_in_category',
            'goods_limit',
            'active_pages',
            'shown_page',
        ];
    }
}
