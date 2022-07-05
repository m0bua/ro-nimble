<?php
/**
 * Класс для создания кастомного фильтра "Статус товара"
 * Class SellStatusService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Filters;

class SellStatusService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->sellStatusFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->filters->sellStatuses->hideValues();
        $data = $this->elasticWrapper->prepareAggrData(
            $this->getData(),
            $this->sellStatusFilterComponent::AGGR_SELL_STATUS
        );
        $this->filters->sellStatuses->showValues();

        if (!$data) {
            return [];
        }

        $sellStatuses = [];
        $order = 0;

        foreach ($data as $status => $count) {
            $sellStatus = [
                'is_chosen' => false,
                'order' => $order,
                'products_quantity' => $count,
                'option_value_id' => $status,
                'option_value_name' => $status,
                'option_value_title' => __('filters.' . $status),
            ];

            $order++;

            // установка выбранных фильтров
            if ($this->filters->sellStatuses->getValues()->contains($status)) {
                $sellStatus['is_chosen'] = true;

                $this->chosen[Filters::PARAM_SELL_STATUSES][$sellStatus['option_value_name']] = [
                    'id' => $sellStatus['option_value_id'],
                    'name' => $sellStatus['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_SELL_STATUSES),
                    'option_value_title' => $sellStatus['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $sellStatuses[] = $sellStatus;
        }

        return [
            Filters::PARAM_SELL_STATUSES => [
                'option_id' => Filters::PARAM_SELL_STATUSES,
                'option_name' => Filters::PARAM_SELL_STATUSES,
                'option_title' => __('filters.' . Filters::PARAM_SELL_STATUSES),
                'option_type' => Filters::OPTION_TYPE_COMBOBOX,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($sellStatuses),
                'option_values' => $sellStatuses
        ]];
    }
}
