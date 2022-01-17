<?php
/**
 * Класс для генерации запроса для фильтра "Дерево категорий"
 * Class SectionFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class SectionFilterComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggs(
            $this->elasticWrapper->aggsComposite(
                Elastic::FIELD_CATEGORIES_PATH,
                Elastic::FIELD_CATEGORIES_PATH,
                Config::FILTERS_AGGREGATIONS_LIMIT
            )
        );
    }
}
