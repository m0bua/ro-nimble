<?php
/**
 * Класс для генерации параметра "pl_bonus_charge_pcs"
 * Class BonusComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class BonusComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->bonus->getValues();

        if ($params->isEmpty()) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        return $this->elasticWrapper->range(Elastic::FIELD_BONUS, [$this->elasticWrapper::RANGE_GT => 0]);
    }
}
