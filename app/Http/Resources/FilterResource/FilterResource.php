<?php

namespace App\Http\Resources\FilterResource;

use App\Enums\Resources;
use App\Http\Resources\BaseResource;
use App\Http\Resources\FilterResource\General\GeneralOptionResource;
use App\Http\Resources\FilterResource\Sliders\SlidersOptionResource;
use App\Http\Resources\FilterResource\Specific\SpecificOptionResource;

class FilterResource extends BaseResource
{
    public $preserveKeys = true;

    /**
     * @return array
     */
    public function getResourceFields(): array
    {
        return [
            [
                'field' => Resources::OPTIONS,
                'alias' => Resources::OPTIONS,
                'inside' => [
                    [
                        'field' => Resources::OPTIONS_GENERAL,
                        'resource' => [
                            'class' => GeneralOptionResource::class,
                            'method' => 'collection',
                        ],
                    ],
                    [
                        'field' => Resources::OPTIONS_SPECIFIC,
                        'resource' => [
                            'class' => SpecificOptionResource::class,
                            'method' => 'make',
                        ],
                    ],
                    [
                        'field' => Resources::OPTIONS_SLIDERS,
                        'resource' => [
                            'class' => SlidersOptionResource::class,
                            'method' => 'collection',
                        ],
                    ],
                ],
            ],
            Resources::CHOSEN,
        ];
    }
}
