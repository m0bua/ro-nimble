<?php

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class PaymentsFilterComponent extends BaseComponent
{
    public const AGGR_PAYMENT_IDS = 'payment_ids';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_PAYMENT_IDS,
                Elastic::FIELD_PAYMENT_IDS,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
