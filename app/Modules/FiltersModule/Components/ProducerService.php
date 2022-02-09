<?php
/**
 * Класс для создания кастомного фильтра "Производитель"
 * Class ProducerService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Config;
use App\Enums\Filters;
use App\Models\Eloquent\Producer;
use Illuminate\Support\Collection;

class ProducerService extends BaseComponent
{
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
    public function getValue(): array
    {
        $this->filters->producers->hideValues();
        $foundProducers = $this->elasticWrapper->prepareAggrData(
            $this->getData(),
            $this->producerFilterComponent::AGGR_PRODUCERS
        );
        $this->filters->producers->showValues();

        if (!$foundProducers) {
            return [];
        }

        $foundProducersWithChosen = $this->addChosenToFound($foundProducers);

        /** @var Collection $producersData */
        $producersData = Producer::getProducersForFilters(array_keys($foundProducersWithChosen));

        if (!$producersData->count()) {
            return [];
        }

        $producers = [];
        $order = 0;

        /** @var Producer $producer */
        foreach ($producersData as $producer) {
            // В авторанжированнных категориях только продюсеры с авторанкингом или выбранные
//            if ($this->filters->category->isAutorankingCategory()
//                && !$producer->is_autoranking
//                && !in_array($producer->id, $this->filters->producers->getValues())
//            ) {
//                continue;
//            }

            $producers[$producer->id] = [
                'option_value_id' => $producer->id,
                'option_value_name' => $producer->name,
                'option_value_title' => $producer->title,
                'is_chosen' => false,
                'products_quantity' => $foundProducersWithChosen[$producer->id] ?? 0,
                'order' => $order,
                'is_value_show' => false,
            ];

            $order++;
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
     * @param array $filter
     * @return array
     */
    protected function prepareFilter(array $filter): array
    {
        $rankedValues = $this->sortValuesByRank($filter['option_values']);
        $rankedCount = $rankedValues['rank_count'];
        unset($rankedValues['rank_count']);

        $shortListCount = $rankedCount <= 0 || !$this->filters->category->isAutorankingCategory()
            ? Config::SHORT_LIST_ELEMENTS_COUNT
            : $rankedCount;

        $totalFound = 0;
        $shortList = [];
        $restList = [];

        foreach ($rankedValues as $value) {
            if ($totalFound < Config::SHORT_LIST_ELEMENTS_COUNT
                && (!$this->filters->category->isAutorankingCategory()
                    || ($this->filters->category->isAutorankingCategory() && $totalFound < $shortListCount)
                )
            ) {
                $shortList[] = $value;
            } else {
                $restList[] = $value;
            }

            $totalFound++;
        }

        $filter['short_list'] = $shortList;
        $filter['option_values'] = $restList;
        $filter['total_found'] = $totalFound;

        return $filter;
    }

    /**
     * @param array $values
     * @return array
     */
    public static function sortValuesByRank(array $values): array
    {
        $rankedList = [];
        $notRankedList = [];

        foreach ($values as $value) {
            if ($value['is_value_show']) {
                $rankedList[] = $value;
            } else {
                $notRankedList[] = $value;
            }
        }

        $rankedList = collect($rankedList)->sortBy('option_value_title')->values()->all();
        $notRankedList = collect($notRankedList)->sortBy('option_value_title')->values()->all();

        $mergedList = array_merge($rankedList, $notRankedList);
        $mergedList['rank_count'] = count($rankedList);

        return $mergedList;
    }

}
