<?php

namespace App\Http\Resources\Search\Brands;

use App\Enums\Resources;
use App\Http\Resources\BaseResource;
use App\Http\Resources\FilterResource\Specific\ProducerOptionValuesResource;

class SearchBrands extends BaseResource
{
    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            [
                'field' => Resources::OPTIONS,
                    'resource' => [
                        'class' => ProducerOptionValuesResource::class,
                        'method' => 'collection'
                    ]
            ]
        ];
    }
}
