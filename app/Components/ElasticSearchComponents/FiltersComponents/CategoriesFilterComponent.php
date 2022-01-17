<?php
/**
 * Класс для генерации запроса для фильтра "Категории товаров"
 * Class CategoriesFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class CategoriesFilterComponent extends BaseComponent
{
    public const AGGR_CATEGORIES = 'categories';

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsTerms(
                self::AGGR_CATEGORIES,
                Elastic::FIELD_CATEGORY_ID,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
