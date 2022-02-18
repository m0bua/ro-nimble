<?php
/**
 * Класс генерации запроса агрегации для динамических фильтров - слайдеров
 * Class OptionSlidersFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class OptionSlidersFilterComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            Elastic::FIELD_OPTION_SLIDERS => [
                'nested' => [
                    'path' => Elastic::FIELD_OPTION_SLIDERS,
                ],
                'aggs' => [
                    Elastic::FIELD_OPTION_SLIDERS => [
                        'terms' => [
                            'field' => Elastic::FIELD_OPTION_SLIDERS_ID,
                            'size' => Config::FILTERS_AGGREGATIONS_LIMIT,
                        ],
                        'aggs' => [
                            'min' => [
                                'min' => [
                                    'field' => Elastic::FIELD_OPTION_SLIDERS_VALUE
                                ]
                            ],
                            'max' => [
                                'max' => [
                                    'field' => Elastic::FIELD_OPTION_SLIDERS_VALUE
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ];
    }
}
