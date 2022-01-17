<?php
/**
 * Класс для генерации параметра "categories_path" для фильтра "category_id"
 * Class CategoryComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class CategoryComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_CATEGORIES_PATH, $this->filters->category->getValues()->first());
    }
}
