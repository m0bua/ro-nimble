<?php
/**
 * Класс для генерации параметра "categories_path"
 * Class SectionComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class SectionComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_CATEGORIES_PATH, $this->filters->section->getValues()->first());
    }
}
