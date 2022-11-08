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

        return $this->elasticWrapper->nested(
            Elastic::FIELD_PROMOTION,
            $this->elasticWrapper->bool(
                $this->elasticWrapper->filter([
                    $this->elasticWrapper->term(Elastic::FIELD_PROMOTION_ID, $param)
                ])
            )
        );
    }
}
