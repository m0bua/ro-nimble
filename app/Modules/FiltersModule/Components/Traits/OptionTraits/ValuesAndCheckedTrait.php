<?php
namespace App\Modules\FiltersModule\Components\Traits\OptionTraits;

use App\Enums\Config;
use App\Filters\Components\Options\OptionChecked;
use App\Models\Eloquent\Option;
use Illuminate\Support\Collection;

trait ValuesAndCheckedTrait
{
    /**
     * Получаем значения обычных фильтров и чекбоксов
     * @return Collection
     */
    public function getValuesAndChecked(): Collection
    {
        if ($this->optionValuesCount->isEmpty() && $this->optionCheckedCount->isEmpty()) {
            return collect([]);
        }

        return $this->option->getOptionsByCategory($this->getFiltersCategories())
            ->filter(function(Collection $option) {
                if (is_null($option['option_value_id'])) {
                    return $this->optionCheckedCount->has($option['option_id']);
                }

                return $this->optionValuesCount->has($option['option_value_id']);
            })
            ->values();
    }

    /**
     * Подготовка опции
     * @param Collection $option
     * @return Collection
     */
    public function prepareOption(Collection $option): Collection
    {
        return collect([
            'option_id' => $option['option_id'],
            'option_name' => $option['option_name'],
            'option_title' => $option['option_title'],
            'option_type' => $option['type'],
            'title_accusative' => $option['option_title_accusative'],
            'title_genetive' => $option['option_title_genetive'],
            'title_prepositional' => $option['option_title_prepositional'],
            'special_combobox_view' => $option['special_combobox_view'],
            'comparable' => $option['comparable'],
            'hide_block' => $option['hide_block'],
            'option_values' => collect([])
        ]);
    }

    /**
     * Подготовка опций фильтра
     * @param Collection $option
     * @param int $optionValueId
     * @return Collection
     */
    public function prepareOptionValue(Collection $option, int $optionValueId): Collection
    {
        return collect([
            'option_value_id' => $optionValueId,
            'option_value_name' => $option['option_value_name'] ?: $optionValueId,
            'option_value_title' => strip_tags($option['option_value_title']),
            'title_genetive' => $option['option_value_title_genetive'],
            'title_accusative' => $option['option_value_title_accusative'],
            'title_prepositional' => $option['option_value_title_prepositional'],
            'color_hash' => $option['option_value_color'],
            'is_chosen' => false,
            'products_quantity' => $this->optionValuesCount->get($optionValueId, 0),
            'is_value_show' => false
        ]);
    }

    /**
     * Подготовка чекбоксов
     * @param Collection $option
     * @param int $optionValueId
     * @return Collection
     */
    public function prepareOptionChecked(Collection $option, int $optionId): Collection
    {
        return collect([
            'option_value_id' => $optionId,
            'option_value_name' => OptionChecked::FILTER_CHECKBOX_VALUE,
            'option_value_title' => $option['option_title'],
            'title_genetive' => $option['option_title_genetive'],
            'title_accusative' => $option['option_title_accusative'],
            'title_prepositional' => $option['option_title_prepositional'],
            'color_hash' => null,
            'is_chosen' => false,
            'products_quantity' => $this->optionCheckedCount->get($optionId, 0),
            'is_value_show' => false
        ]);
    }

    /**
     * Финальная подготовка фильтров
     * @param Collection $option
     * @return Collection
     */
    public function prepareFilter(Collection $option): Collection
    {
        //слайдеры уже сформированы, их не трогаем
        if (!$option->has('option_values')) {
            return $option;
        }

        $option->put('total_found', $option->get('option_values')->count());
        $option->put('option_values', $option->get('option_values')->splice(0, Config::SHORT_LIST_ELEMENTS_COUNT));

        return $option;
    }


}
