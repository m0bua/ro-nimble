<?php
/**
 * Класс генерации запроса агрегации для динамических фильтров - чекбоксов
 * Class OptionCheckedFilterComponent
 * @package App\Components\ElasticSearchComponents\FiltersComponents
 */

namespace App\Components\ElasticSearchComponents\FiltersComponents;

use App\Components\ElasticSearchComponents\BaseComponent;
use App\Enums\Config;
use App\Enums\Elastic;

class OptionCheckedFilterComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->aggsComposite(
            Elastic::FIELD_OPTION_CHECKED,
            Elastic::FIELD_OPTION_CHECKED,
            Config::FILTERS_AGGREGATIONS_LIMIT
        );
    }
}
