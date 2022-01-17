<?php

namespace App\Http\Resources\FilterResource\Sliders;

use App\Http\Resources\BaseResource;

class SliderConfigResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'unit',
            'values_pattern',
        ];
    }
}
