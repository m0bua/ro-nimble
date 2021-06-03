<?php

namespace App\Http\Resources;

class AnchorResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'title',
            'href',
        ];
    }
}
