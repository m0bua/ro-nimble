<?php
/**
 * Класс для генерации параметра "price"
 * Class PriceComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class PriceComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->price->getValues();

        if ($params->isEmpty()) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        return $this->elasticWrapper->range(Elastic::FIELD_PRICE, [
            $this->elasticWrapper::RANGE_GTE => $params[$this->filters->price::MIN_KEY],
            $this->elasticWrapper::RANGE_LTE => $params[$this->filters->price::MAX_KEY],
        ]);
    }
}
