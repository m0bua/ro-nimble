<?php
/**
 * Класс для генерации параметра "state"
 * Class StateComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class StateComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->states->getValues();

        if ($params->count() > 1) {
            return $this->elasticWrapper->terms(Elastic::FIELD_STATE, $params->toArray());
        } else {
            return $this->elasticWrapper->term(Elastic::FIELD_STATE, $params->first());
        }
    }
}
