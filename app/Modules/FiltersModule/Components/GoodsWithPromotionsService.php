<?php
/**
 * Класс для создания кастомного фильтра "Товары с акциями"
 * Class GoodsWithPromotionsService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Config;
use App\Enums\Elastic;
use App\Enums\Filters;

class GoodsWithPromotionsService extends BaseComponent
{
    /** @var array $currentCustomFiltersConditions */
    private array $currentCustomFiltersConditions;

    /**
     * @param array $filterComponent
     * @return void
     */
    private function setCurrentFilterQuery(array $filterComponent): void
    {
        $this->currentCustomFiltersConditions = $filterComponent;
    }

    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->countFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return $this->currentCustomFiltersConditions;
    }

    /**
     * @return array
     */
    private function installmentsCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->term(Elastic::FIELD_OPTIONS, Config::INSTALLMENT_OPTION);
    }

    /**
     * @return array
     */
    private function promotionsCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->terms(Elastic::FIELD_GOODS_LABELS_IDS, Filters::$filterPromotionTags);
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->filters->goodsWithPromotions->hideValues();
        $this->setCurrentFilterQuery($this->installmentsCustomFiltersConditions());
        $installments = $this->elasticWrapper->prepareCountAggrData($this->getData());

        $this->setCurrentFilterQuery($this->promotionsCustomFiltersConditions());
        $promotions = $this->elasticWrapper->prepareCountAggrData($this->getData());

        $this->filters->goodsWithPromotions->showValues();

        if (!$installments && !$promotions) {
            return [];
        }

        $data = [
            Filters::PROMOTION_GOODS_INSTALLMENT => $installments,
            Filters::PROMOTION_GOODS_PROMOTION => $promotions
        ];

        $goodsWithPromotions = [];
        $order = 0;

        foreach ($data as $filter => $count) {
            if (!$count) {
                continue;
            }

            $option = [
                'option_value_id' => $filter,
                'option_value_name' => $filter,
                'option_value_title' => __('filters.' . $filter),
                'is_chosen' => false,
                'products_quantity' => $count,
                'order' => $order,
            ];

            $order++;

            // установка выбранных фильтров
            if ($this->filters->goodsWithPromotions->getValues()->contains($filter)) {
                $option['is_chosen'] = true;

                $this->chosen[Filters::PARAM_PROMOTION_GOODS][$option['option_value_name']] = [
                    'id' => $option['option_value_id'],
                    'name' => $option['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_PROMOTION_GOODS),
                    'option_value_title' => $option['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $goodsWithPromotions[] = $option;
        }

        return [
            Filters::PARAM_PROMOTION_GOODS => [
                'option_id' => Filters::PARAM_PROMOTION_GOODS,
                'option_name' => Filters::PARAM_PROMOTION_GOODS,
                'option_title' => __('filters.' . Filters::PARAM_PROMOTION_GOODS),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($goodsWithPromotions),
                'option_values' => $goodsWithPromotions
        ]];
    }
}
