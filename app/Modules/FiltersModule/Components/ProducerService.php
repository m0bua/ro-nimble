<?php
/**
 * Класс для создания кастомного фильтра "Производитель"
 * Class ProducerService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Models\Eloquent\Producer;
use App\Modules\FiltersModule\Components\Traits\SortTrait;
use Illuminate\Support\Collection;

class ProducerService extends BaseComponent
{
    use SortTrait;

    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->producerFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getCustomFiltersConditions(): array
    {
        return $this->elasticWrapper->range(Elastic::FIELD_PRODUCER, [$this->elasticWrapper::RANGE_GT => 0]);
    }

    /**
     * Возвращает продюсеры с учетом поиска
     * @return array
     */
    public function searchBrands(): Collection
    {
        $producers = $this->getValue();

        if (!$producers || !isset($producers[Filters::PARAM_PRODUCER])) {
            return collect([]);
        }

        $producers = $producers[Filters::PARAM_PRODUCER];

        return collect(array_merge($producers['short_list'], $producers['option_values']))->recursive();
    }

    /**
     * @return array
     */
    public function getValue(array $response = []): array
    {
        if (empty($response)) {
            $this->filters->producers->hideValues();
            $foundProducers = $this->elasticWrapper->prepareAggrData(
                $this->getData(),
                $this->producerFilterComponent::AGGR_PRODUCERS
            );
            $this->filters->producers->showValues();
        } else {
            $foundProducers = $this->elasticWrapper->prepareAggrData(
                $response,
                $this->producerFilterComponent::AGGR_PRODUCERS
            );
        }

        if (!$foundProducers) {
            return [];
        }

        $foundProducersWithChosen = $this->addChosenToFound($foundProducers);

        /** @var Collection $producersData */
        $producersData = $this->producer->getProducersForFilters(
            array_keys($foundProducersWithChosen),
            $this->filters->category->getValues()
        );

        if (!$producersData->count()) {
            return [];
        }

        $this->isAutorankingCategory = $this->filters->category->isAutorankingCategory();
        $this->isFilterAutoranking = $this->filters->category->isFilterAutoranking();

        if ($this->isAutorankingCategory) {
            // В авторанжированнных категориях только продюсеры с авторанкингом или выбранные
            $producersData = $producersData->filter(function(Collection $producer) {
                return $producer['is_autoranking']
                    || $this->filters->producers->getValues()->contains($producer['id']);
            });
        }

        if (!$producersData->count()) {
            return [];
        }

        $producers = [];

        /** @var Producer $producer */
        foreach ($producersData as $producer) {
            $id = $producer['id'];
            $producers[$id] = [
                'option_value_id' => $id,
                'option_value_name' => $producer['name'],
                'option_value_title' => $producer['title'] ?? '',
                'option_value_image' => $producer['image'] ?? null,
                'is_chosen' => false,
                'products_quantity' => $foundProducersWithChosen[$id] ?? 0,
                'is_value_show' => !!$producer['is_value_show'],
            ];
        }

        // установка выбранных фильтров
        foreach ($this->filters->producers->getValues() as $producerId) {
            if (!empty($producers[$producerId])) {
                $producers[$producerId]['is_chosen'] = true;

                $this->chosen[Filters::PARAM_PRODUCER][$producers[$producerId]['option_value_name']] = [
                    'id' => $producerId,
                    'name' => $producers[$producerId]['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_PRODUCER),
                    'option_value_title' => $producers[$producerId]['option_value_title'],
                    'option_value_image' => $producers[$producerId]['option_value_image'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }
        }

        return [
            Filters::PARAM_PRODUCER => $this->prepareFilter([
                'option_id' => Filters::PARAM_PRODUCER,
                'option_name' => Filters::PARAM_PRODUCER,
                'option_title' => __('filters.' . Filters::PARAM_PRODUCER),
                'option_type' => Filters::OPTION_TYPE_LIST,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_SECTION_LIST_AUTOCOMPLETE,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => 0,
                'option_values' => $producers,
                'short_list' => []
            ])
        ];
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        $this->filters->producers->hideValues();
        $query = $this->getDataQuery();
        $this->filters->producers->showValues();
        return [$this->producerFilterComponent::AGGR_PRODUCERS => $query];
    }

    /**
     * @inerhitDoc
     * @param $response
     * @return array
     */
    public function getValueFromMSearch($response): array
    {
        return $this->getValue($response);
    }

    /**
     * Додает выбранные производители к найденным
     * Метод реализован в связи с тем, что поиск работает по всем производителям и выбранные могут отсутствовать в выдаче
     * @param array $foundProducers
     * @return array
     */
    public function addChosenToFound(array $foundProducers): array
    {
        foreach ($this->filters->producers->getValues() as $producerId) {
            if (empty($foundProducers[$producerId])) {
                $foundProducers[$producerId] = null;
            }
        }

        return $foundProducers;
    }

    /**
     * Дополняем фильтр недостающими данными и отсортированными значениями
     * @param array $filter
     * @return array
     */
    private function prepareFilter(array $filter): array
    {
        return array_merge($filter, $this->getSortedValues($filter['option_values']));
    }
}
