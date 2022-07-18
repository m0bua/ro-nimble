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

        $installment = [];
        $promotion = [];
        foreach ($params as $param) {
            if ($param == Filters::PROMOTION_GOODS_INSTALLMENT) {
                $installment[] = $this->elasticWrapper->term(Elastic::FIELD_OPTIONS, Config::INSTALLMENT_OPTION);
            } elseif ($param == Filters::PROMOTION_GOODS_PROMOTION) {
                $promotion[] = $this->elasticWrapper->terms(Elastic::FIELD_GOODS_LABELS_IDS, Filters::$filterPromotionTags);
            }
        }

        $result[] = $this->elasticWrapper->bool(
            $this->elasticWrapper->should(
                array_merge($installment, $promotion)
            )
        );

        return $result;
    }
}
