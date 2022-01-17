<?php
/**
 * Класс для генерации параметра "producer_id"
 * Class ProducerComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class ProducerComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->producers->getValues();

        if ($params->count() > 1) {
            return $this->elasticWrapper->terms(Elastic::FIELD_PRODUCER, $params->toArray());
        } else {
            return $this->elasticWrapper->term(Elastic::FIELD_PRODUCER, $params->first());
        }
    }
}
