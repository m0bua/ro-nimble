<?php
/**
 * Класс для генерации запроса для значений опций динамических фильтров
 * Class OptionValuesFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class OptionValuesFilterComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggsComposite(
            Elastic::FIELD_OPTION_VALUES,
            Elastic::FIELD_OPTION_VALUES,
            Config::FILTERS_AGGREGATIONS_LIMIT
        );
    }
}
