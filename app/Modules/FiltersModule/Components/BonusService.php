<?php
/**
 * Класс для создания кастомного фильтра "Программа лояльности"
 * Class BonusService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;

class BonusService extends BaseComponent
{
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
        return $this->elasticWrapper->range(Elastic::FIELD_BONUS, [$this->elasticWrapper::RANGE_GT => 0]);
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->filters->bonus->hideValues();
        $data = $this->elasticWrapper->prepareCountAggrData($this->getData());
        $this->filters->bonus->showValues();

        if (!$data) {
            return [];
        }

        $with_bonus = [
            'option_value_id' => Filters::PARAM_BONUS,
            'option_value_name' => Filters::PARAM_BONUS,
            'option_value_title' => __('filters.' . Filters::PARAM_BONUS),
            'is_chosen' => $this->filters->bonus->getValues()->isNotEmpty(),
            'products_quantity' => $data,
            'order' => 0,
        ];

        // установка выбранных фильтров
        if ($this->filters->bonus->getValues()->isNotEmpty()) {
            $this->chosen[Filters::PARAM_BONUS][$with_bonus['option_value_name']] = [
                'id' => $with_bonus['option_value_id'],
                'name' => $with_bonus['option_value_name'],
                'option_title' => __('filters.' . Filters::BONUS),
                'option_value_title' => $with_bonus['option_value_title'],
                'comparable' => Filters::COMPARABLE_MAIN,
            ];
        }

        return [
            Filters::PARAM_BONUS => [
                'option_id' => Filters::PARAM_BONUS,
                'option_name' => Filters::PARAM_BONUS,
                'option_title' => __('filters.' . Filters::BONUS),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => 1,
                'option_values' => [$with_bonus]
        ]];
    }
}
