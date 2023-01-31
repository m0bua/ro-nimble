<?php
/**
 * Класс для создания кастомного фильтра "Серия"
 * Class SeriesService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Models\Eloquent\Series;
use Illuminate\Support\Collection;

class SeriesService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->seriesFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->range(Elastic::FIELD_SERIES, [$this->elasticWrapper::RANGE_GT => 0]);
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        if (!$this->filters->producers->isViewSeries()) {
            return [];
        }

        $this->filters->series->hideValues();
        $query = $this->getDataQuery();
        $this->filters->series->showValues();
        return [$this->seriesFilterComponent::AGGR_SERIES => $query];
    }

    /**
     * @inerhitDoc
     * @param array $response
     * @return array
     */
    public function getValueFromMSearch(array $response): array
    {
        $foundSeries = $this->elasticWrapper->prepareAggrData(
            $response,
            $this->seriesFilterComponent::AGGR_SERIES
        );

        if (!$foundSeries) {
            return [];
        }

        /** @var Collection $seriesData */
        $seriesData = Series::getSeriesForFilters(array_keys($foundSeries));

        if (!$seriesData->count()) {
            return [];
        }

        $optionValues = [];
        $order = 0;

        /** @var Series $series */
        foreach ($seriesData as $series) {
            $optionValues[$series->id] = [
                'option_value_id' => $series->id,
                'option_value_name' => $series->name,
                'option_value_title' => $series->title,
                'is_chosen' => false,
                'products_quantity' => $foundSeries[$series->id] ?? 0,
                'order' => $order,
            ];
        }

        // установка выбранных фильтров
        foreach ($this->filters->series->getValues() as $seriesId) {
            if (!empty($optionValues[$seriesId])) {
                $optionValues[$seriesId]['is_chosen'] = true;

                $this->chosen[Filters::PARAM_SERIES][$optionValues[$seriesId]['option_value_name']] = [
                    'id' => $seriesId,
                    'name' => $optionValues[$seriesId]['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_SERIES),
                    'option_value_title' => $optionValues[$seriesId]['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }
        }

        $optionValues = collect($optionValues)->sortBy('option_value_title')->values()->all();

        return [
            Filters::PARAM_SERIES => [
                'option_id' => Filters::PARAM_SERIES,
                'option_name' => Filters::PARAM_SERIES,
                'option_title' => __('filters.' . Filters::PARAM_SERIES),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($optionValues),
                'option_values' => $optionValues
            ]
        ];
    }
}
