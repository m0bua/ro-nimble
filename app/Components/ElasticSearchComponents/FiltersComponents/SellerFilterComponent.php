<?php
/**
 * Класс для генерации запроса для фильтра "Продавец"
 * Class SellerFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class SellerFilterComponent extends BaseComponent
{
    public const AGGR_SELLERS = 'sellers';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_SELLERS,
                Elastic::FIELD_MERCHANT,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
