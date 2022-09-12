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
        $params = $this->filters->sellStatuses->getValues()->toArray();
        if (empty($params)) {
            $params = $this->filters->category->isFashion()
                ? Filters::$sellActiveStatusesFashion
                : Filters::$sellActiveStatuses;
        }
        return $this->elasticWrapper->terms(Elastic::FIELD_SELL_STATUS, $params);
    }
}
