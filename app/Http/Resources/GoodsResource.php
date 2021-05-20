<?php

namespace App\Http\Resources;

class GoodsResource extends BaseResource
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
