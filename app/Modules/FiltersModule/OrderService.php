<?php
/**
 * Class OrderService
 * @package App\Modules\FiltersModule
 */

namespace App\Modules\FiltersModule;

use App\Enums\Filters;
use Illuminate\Support\Collection;

class OrderService
{
    /**
     * Последовательность вывода фильтров
     *
     * Порядок вывода первых фильтров в списке
     * @return array
     */
    public array $firstsFiltersOrder = [
        Filters::PARAM_SECTION,
        Filters::PARAM_RASPRODAGA,
        Filters::PARAM_CATEGORY,
        Filters::PARAM_SELLER,
        Filters::PARAM_GOTOVO_K_OTPRAVKE,
        Filters::PARAM_PRODUCERS,
        Filters::PARAM_SERIES,
        Filters::PARAM_PRICE,
    ];
    /**
     * Порядок вывода последних фильтров в списке
     * @return array
     */
    public array $lastsFiltersOrder = [
        Filters::PARAM_PROMOTION_GOODS,
        Filters::PARAM_BONUS,
        Filters::PARAM_STATE,
        Filters::PARAM_SELL_STATUS,
    ];

    /**
     * @var int
     */
    private int $currentOptionOrder = 0;

    /**
     * @var int
     */
    private int $currentOptionValueOrder = 0;

    /**
     * @var Collection
     */
    private Collection $currentOptionsNames;

    /**
     * @var Collection
     */
    private Collection $firstsFilters;

    /**
     * @var Collection
     */
    private Collection $lastsFilters;

    public function __construct() {
        $this->firstsFilters = collect([]);
        $this->lastsFilters = collect([]);
    }

    /**
     * Добавление параметра order для сортировки фильтров
     * @param Collection $options
     * @return Collection
     */
    public function orderOptions(Collection $options): Collection
    {
        if ($options->isEmpty()) {
            return collect([]);
        }

        $this->currentOptionsNames = $options->pluck('option_name');

        collect($this->firstsFiltersOrder)->each(function ($filterKey) use ($options) {
            $filterKey = $this->currentOptionsNames->search($filterKey);

            if(false !== $filterKey) {
                $this->firstsFilters->push($options->pull($filterKey));
            }
        });

        collect($this->lastsFiltersOrder)->each(function ($filterKey) use ($options) {
            $filterKey = $this->currentOptionsNames->search($filterKey);

            if(false !== $filterKey) {
                $this->lastsFilters->push($options->pull($filterKey));
            }
        });

        return $this->firstsFilters
            ->merge($options->values())
            ->merge($this->lastsFilters)
            ->each(function(Collection $option) {
                $option->put('order', $this->currentOptionOrder++);

                if ($option->has('option_values')) {
                    $this->currentOptionValueOrder = 0;

                    $option['option_values']->each(function ($optionValue) {
                        $optionValue->put('order', $this->currentOptionValueOrder++);
                    });
                }
            });
    }
}