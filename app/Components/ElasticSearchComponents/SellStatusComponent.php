<?php
/**
 * Класс для генерации параметра "sell_status"
 * Class SellStatusComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;
use App\Enums\Filters;

class SellStatusComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $result = $this->elasticWrapper::DEFAULT_RESULT;

        $params = $this->filters->sellStatuses->getValues()->toArray();
        if (empty($params)) {
            if($this->filters->category->isFashion()) {
                $result = $this->elasticWrapper->terms(Elastic::FIELD_SELL_STATUS, Filters::$sellActiveStatusesFashion);
            }
        } else {
            $result = $this->elasticWrapper->terms(Elastic::FIELD_SELL_STATUS, $params);
        }

        return $result;
    }
}
