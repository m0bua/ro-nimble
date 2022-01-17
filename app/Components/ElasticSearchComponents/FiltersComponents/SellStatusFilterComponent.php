<?php
/**
 * Класс для генерации запроса для фильтра "Статус товара"
 * Class SellStatusFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class SellStatusFilterComponent extends BaseComponent
{
    public const AGGR_SELL_STATUS = 'sell_status';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_SELL_STATUS,
                Elastic::FIELD_SELL_STATUS,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
