<?php
/**
 * Класс для генерации параметра "status_inherited"
 * Class StatusInheritedComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;
use App\Enums\Filters;

class StatusInheritedComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_STATUS_INHERITED, Filters::STATUS_INHERITED_ACTIVE);
    }
}
