<?php
/**
 * Класс для генерации запроса для фильтра "producer"
 * Class ProducerFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class ProducerFilterComponent extends BaseComponent
{
    public const AGGR_PRODUCERS = 'producers';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_PRODUCERS,
                Elastic::FIELD_PRODUCER,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
