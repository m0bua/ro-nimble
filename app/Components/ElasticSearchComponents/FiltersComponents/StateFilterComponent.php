<?php
/**
 * Класс для генерации запроса для фильтра "Б\У - Новый"
 * Class StateFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class StateFilterComponent extends BaseComponent
{
    public const AGGR_STATE = 'state';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_STATE,
                Elastic::FIELD_STATE,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
