<?php
/**
 * Класс для создания кастомного фильтра "Продавец"
 * Class SellerService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Filters;

class SellerService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->sellerFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->filters->sellers->hideValues();
        $data = $this->elasticWrapper->prepareAggrData($this->getData(), $this->sellerFilterComponent::AGGR_SELLERS);
        $this->filters->sellers->showValues();

        if (!$data) {
            return [];
        }

        $valuesTemplates = $this->getValuesTemplates();
        $sellers = [];

        foreach ($valuesTemplates as $merchantType => $seller) {
            if (empty($data[$merchantType])) {
                continue;
            }

            $seller['products_quantity'] = $data[$merchantType];

            // установка выбранных фильтров
            if ($this->filters->sellers->getValues()->contains($merchantType)) {
                $seller['is_chosen'] = true;

                $this->chosen[Filters::PARAM_SELLERS][$seller['option_value_name']] = [
                    'id' => $seller['option_value_id'],
                    'name' => $seller['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_SELLERS),
                    'option_value_title' => $seller['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $sellers[] = $seller;
        }

        return [
            Filters::PARAM_SELLERS => [
                'option_id' => Filters::PARAM_SELLERS,
                'option_name' => Filters::PARAM_SELLERS,
                'option_title' => __('filters.' . Filters::PARAM_SELLERS),
                'option_type' => Filters::OPTION_TYPE_COMBOBOX,
                'title_genetive' => __('filters.' . Filters::PARAM_SELLERS),
                'title_accusative' => __('filters.' . Filters::PARAM_SELLERS),
                'title_prepositional' => __('filters.' . Filters::PARAM_SELLERS),
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($sellers),
                'option_values' => $sellers
        ]];
    }

    /**
     * Шаблон для значений фильтра `Продавец`
     * @return array[]
     */
    protected function getValuesTemplates(): array
    {
        return [
            $this->filters->sellers::MERCHANT_TYPE_ROZETKA => [
                'option_value_id' => Filters::SELLER_ROZETKA,
                'option_value_name' => Filters::SELLER_ROZETKA,
                'option_value_title' => 'Rozetka',
                'title_genetive' => 'Rozetka',
                'title_accusative' => 'Rozetka',
                'title_prepositional' => 'Rozetka',
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 0,
            ],
            $this->filters->sellers::MERCHANT_TYPE_OTHER => [
                'option_value_id' => Filters::SELLER_OTHER,
                'option_value_name' => Filters::SELLER_OTHER,
                'option_value_title' => __('filters.seller_other'),
                'title_genetive' => __('filters.seller_other'),
                'title_accusative' => __('filters.seller_other'),
                'title_prepositional' => __('filters.seller_other'),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 1,
            ],
            $this->filters->sellers::MERCHANT_TYPE_FULFILLMENT => [
                'option_value_id' => Filters::SELLER_FULFILLMENT,
                'option_value_name' => Filters::SELLER_FULFILLMENT,
                'option_value_title' => __('filters.seller_fulfillment'),
                'title_genetive' => __('filters.seller_fulfillment'),
                'title_accusative' => __('filters.seller_fulfillment'),
                'title_prepositional' => __('filters.seller_fulfillment'),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 2,
            ]
        ];
    }
}
