<?php
/**
 * Класс для генерации запроса для фильтра "series"
 * Class SeriesFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class SeriesFilterComponent extends BaseComponent
{
    public const AGGR_SERIES = 'series';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_SERIES,
                Elastic::FIELD_SERIES,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
