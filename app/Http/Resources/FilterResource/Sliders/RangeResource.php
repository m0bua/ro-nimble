<?php

namespace App\Http\Resources\FilterResource\Sliders;

use App\Http\Resources\BaseResource;

class RangeResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'min',
            'max',
        ];
    }
}
