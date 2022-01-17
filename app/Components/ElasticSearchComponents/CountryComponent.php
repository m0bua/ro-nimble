<?php
/**
 * Класс для генерации параметра "categories_path" для фильтра "country"
 * Class CountryComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class CountryComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_COUNTRY_CODE, $this->filters->country->getValues()->first());
    }
}
