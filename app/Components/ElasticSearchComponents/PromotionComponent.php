<?php
/**
 * Класс для генерации параметра "promotion_id"
 * Class PromotionComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Elastic;

class PromotionComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $param = $this->filters->promotion->getValues()->first();

        if (!$param) {
            return $this->elasticWrapper::DEFAULT_RESULT;
        }

        return $this->elasticWrapper->term(Elastic::FIELD_PROMOTION_IDS, $param);
    }
}
