<?php

namespace App\Http\Resources\CategoryGetChildrenResource;

use App\Http\Resources\BaseResource;

class CategoryGetChildrenResource extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            'current',
            'children',
        ];
    }
}
