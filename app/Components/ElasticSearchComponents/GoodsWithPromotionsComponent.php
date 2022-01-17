<?php
/**
 * Класс для генерации условия для фильтра "Товары с акциями"
 * Class GoodsWithPromotionsComponent
 * @package App\Components\ElasticSearchComponents
 */

namespace App\Components\ElasticSearchComponents;

use App\Enums\Config;
use App\Enums\Elastic;
use App\Enums\Filters;

class GoodsWithPromotionsComponent extends BaseComponent
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        $params = $this->filters->goodsWithPromotions->getValues();
        $result = $this->elasticWrapper::DEFAULT_RESULT;

        if ($params->isEmpty()) {
            return $result;
        }

        foreach ($params as $param) {
            if ($param == Filters::PROMOTION_GOODS_INSTALLMENT) {
                $result[] = $this->elasticWrapper->term(Elastic::FIELD_OPTIONS, Config::INSTALLMENT_OPTION);
            } elseif ($param == Filters::PROMOTION_GOODS_PROMOTION) {
                $result[] = $this->elasticWrapper->terms(Elastic::FIELD_TAGS, Filters::$filterPromotionTags);
            }
        }

        return $result;
    }
}
