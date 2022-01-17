<?php
/**
 * Класс для генерации запроса для фильтра "Цена"
 * Class PriceFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Elastic;

class PriceFilterComponent extends BaseComponent
{
    public const AGGR_KEY_MIN_PRICE = 'min_price';
    public const AGGR_KEY_MAX_PRICE = 'max_price';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs([
            self::AGGR_KEY_MIN_PRICE => [
                'min' => [
                    'field' => Elastic::FIELD_PRICE
                ]
            ],
            self::AGGR_KEY_MAX_PRICE => [
                'max' => [
                    'field' => Elastic::FIELD_PRICE
                ]
            ]
        ]);
    }
}
