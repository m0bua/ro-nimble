<?php
/**
 * Класс генерации запроса агрегации опций динамических фильтров
 * Class OptionValuesFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class OptionsFilterComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggsComposite(
            Elastic::FIELD_OPTIONS,
            Elastic::FIELD_OPTIONS,
            Config::FILTERS_AGGREGATIONS_LIMIT
        );
    }
}
