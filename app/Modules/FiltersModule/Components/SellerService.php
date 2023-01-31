<?php
/**
 * Класс для создания кастомного фильтра "Продавец"
 * Class SellerService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Components\ElasticSearchComponents\FiltersComponents\SellerFilterComponent;
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
     * @return string
     */
    public static function getAggKey(): string
    {
        return SellerFilterComponent::AGGR_SELLERS;
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        $queries = [];
        $this->filters->sellers->hideValues();
        $queries[] = $this->getDataQuery();
        $this->filters->sellers->showValues();

        return $queries;
    }

    /**
     * @inerhitDoc
     * @param $response
     * @return array
     */
    public function getValueFromMSearch($response): array
    {
        $data = $this->elasticWrapper->prepareAggrData(
            $response,
            $this->sellerFilterComponent::AGGR_SELLERS
        );

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
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($sellers),
                'option_values' => $sellers
            ]
        ];
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
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 0,
            ],
            $this->filters->sellers::MERCHANT_TYPE_OTHER => [
                'option_value_id' => Filters::SELLER_OTHER,
                'option_value_name' => Filters::SELLER_OTHER,
                'option_value_title' => __('filters.seller_other'),
                'is_chosen' => false,
                'products_quantity' => 0,
                'order' => 1,
            ],
        ];
    }
}
