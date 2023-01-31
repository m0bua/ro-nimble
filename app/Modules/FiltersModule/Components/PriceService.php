<?php
/**
 * Класс для создания кастомного фильтра "Цена"
 * Class PriceService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Helpers\CountryHelper;

class PriceService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->priceFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->range(Elastic::FIELD_PRICE, [$this->elasticWrapper::RANGE_GT => 0]);
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        $this->filters->price->hideValues();
        $query = $this->getDataQuery();
        $this->filters->price->showValues();
        return [$query];
    }

    /**
     * @inerhitDoc
     * @param array $response
     * @return array
     */
    public function getValueFromMSearch(array $response): array
    {
        $data = $this->prepareData($response);

        if (!$data[$this->priceFilterComponent::AGGR_KEY_MIN_PRICE] && !$data[$this->priceFilterComponent::AGGR_KEY_MAX_PRICE]) {
            return [];
        }

        $range = $this->calcStrictRange($data);

        if (!$range[$this->filters->price::MIN_KEY] && !$range[$this->filters->price::MAX_KEY]) {
            return [];
        }

        if ($this->filters->price->getValues()->isNotEmpty()) {
            $chosenValues = $this->filters->price->getValues();

            $stringRange = sprintf('%s - %s',
                $chosenValues[$this->filters->price::MIN_KEY],
                $chosenValues[$this->filters->price::MAX_KEY]
            );

            $this->chosen[Filters::PARAM_PRICE]['range'] = [
                'id' => Filters::PARAM_PRICE,
                'name' => $stringRange,
                'option_title' => __('filters.' . Filters::PARAM_PRICE),
                'option_value_title' => $stringRange,
                'comparable' => Filters::COMPARABLE_MAIN,
                'unit' => CountryHelper::isUaCountry() ? 'грн' : 'сум'
            ];
        } else {
            $chosenValues = $range;
        }

        return [
            Filters::PARAM_PRICE => [
                'option_id' => Filters::PARAM_PRICE,
                'option_name' => Filters::PARAM_PRICE,
                'option_title' => __('filters.' . Filters::PARAM_PRICE),
                'option_type' => Filters::OPTION_TYPE_SLIDER,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_SLIDER,
                'comparable' => Filters::COMPARABLE_MAIN,
                'range_values' => $range,
                'chosen_values' => $chosenValues
            ]
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareData(array $data): array
    {
        $result = [];

        foreach ($data['aggregations'] as $key => $value) {
            $result[$key] = $value['value'] ?? 0;
        }

        return $result;
    }

    /**
     * @param array $queryRange
     * @return array
     */
    public function calcStrictRange(array $queryRange): array
    {
        $min = !empty($this->filters->price->getValues()[$this->filters->price::MIN_KEY])
            ? min([
                $queryRange[$this->priceFilterComponent::AGGR_KEY_MIN_PRICE],
                $this->filters->price->getValues()[$this->filters->price::MIN_KEY]
            ])
            : $queryRange[$this->priceFilterComponent::AGGR_KEY_MIN_PRICE];

        $max = max([
            $queryRange[$this->priceFilterComponent::AGGR_KEY_MAX_PRICE],
            $this->filters->price->getValues()[$this->filters->price::MAX_KEY] ?? 0
        ]);

        return [
            $this->filters->price::MIN_KEY => floor($min),
            $this->filters->price::MAX_KEY => ceil($max)
        ];
    }
}
