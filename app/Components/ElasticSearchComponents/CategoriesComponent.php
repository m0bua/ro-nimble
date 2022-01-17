<?php
/**
 * Класс для генерации параметра "categories_path" для фильтра "categories"
 * Class CategoriesComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class CategoriesComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->categories->getValues();

        if ($params->count() > 1) {
            return $this->elasticWrapper->terms(Elastic::FIELD_CATEGORIES_PATH, $params->toArray());
        } else {
            return $this->elasticWrapper->term(Elastic::FIELD_CATEGORIES_PATH, $params->first());
        }
    }
}
