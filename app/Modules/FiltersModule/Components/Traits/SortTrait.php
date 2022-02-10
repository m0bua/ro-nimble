<?php
namespace App\Modules\FiltersModule\Components\Traits;

use App\Enums\Config;

trait SortTrait
{
    /**
     * @var bool
     */
    private bool $isFilterAutoranking = false;

    /**
     * @var bool
     */
    private bool $isAutorankingCategory = false;

    /**
     * @param array $filter
     * @return array
     */
    private function getSortedValues(array $optionValues): array
    {
        $rankedList = [];
        $notRankedList = [];

        foreach ($optionValues as $value) {
            if ($value['is_value_show']) {
                $rankedList[] = $value;
            } else {
                $notRankedList[] = $value;
            }
        }

        $rankedList = collect($rankedList)->sortBy('option_value_title')->values();
        $notRankedList = collect($notRankedList)->sortBy('option_value_title')->values();
        $rankedCount = $rankedList->count();
        $optionValues = $rankedList->merge($notRankedList);

        $shortListCount = $this->isFilterAutoranking && $rankedCount < Config::SHORT_LIST_ELEMENTS_COUNT
            ? $rankedCount
            : Config::SHORT_LIST_ELEMENTS_COUNT;

        $shortList = $optionValues->splice(0, $shortListCount);
        $optionValues = $optionValues->sortBy('option_value_title')->values();

        return [
            'total_found' => $shortList->count() + $optionValues->count(),
            'short_list' => $shortList->toArray(),
            'option_values' => $optionValues->toArray()
        ];
    }
}
