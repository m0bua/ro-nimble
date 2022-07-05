<?php
/**
 * Класс для создания кастомного фильтра "Б\У - Новый"
 * Class StateService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Filters;

class StateService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->stateFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->filters->states->hideValues();
        $data = $this->elasticWrapper->prepareAggrData($this->getData(), $this->stateFilterComponent::AGGR_STATE);
        $this->filters->states->showValues();

        if (!$data) {
            return [];
        }

        $valuesTemplates = $this->getValuesTemplates();

        $states = [];

        foreach ($data as $status => $count) {
            $state = $valuesTemplates[$status];
            $state['products_quantity'] = $count;

            // установка выбранных фильтров
            if ($this->filters->states->getValues()->contains($status)) {
                $state['is_chosen'] = true;

                $this->chosen[Filters::PARAM_STATES][$state['option_value_name']] = [
                    'id' => $state['option_value_id'],
                    'name' => $state['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_STATES),
                    'option_value_title' => $state['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $states[] = $state;
        }

        return [
            Filters::PARAM_STATES => [
                'option_id' => Filters::PARAM_STATES,
                'option_name' => Filters::PARAM_STATES,
                'option_title' => __('filters.' . Filters::PARAM_STATES),
                'option_type' => Filters::OPTION_TYPE_COMBOBOX,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($states),
                'option_values' => $states
        ]];
    }

    /**
     * Шаблон для значений фильтра `Продавец`
     * @return array[]
     */
    protected function getValuesTemplates(): array
    {
        return [
            Filters::STATE_NEW => [
                'option_value_id' => Filters::STATE_NEW,
                'option_value_name' => Filters::STATE_NEW,
                'option_value_title' => __('filters.' . Filters::STATE_NEW),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 0,
            ],
            Filters::STATE_USED => [
                'option_value_id' => Filters::STATE_USED,
                'option_value_name' => Filters::STATE_USED,
                'option_value_title' => __('filters.' . Filters::STATE_USED),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 1,
            ],
            Filters::STATE_REFURBISHED => [
                'option_value_id' => Filters::STATE_REFURBISHED,
                'option_value_name' => Filters::STATE_REFURBISHED,
                'option_value_title' => __('filters.' . Filters::STATE_REFURBISHED),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 2,
            ]
        ];
    }
}
