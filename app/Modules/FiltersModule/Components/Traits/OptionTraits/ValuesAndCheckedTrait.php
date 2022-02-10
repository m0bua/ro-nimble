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
        if (
            $this->optionsCount->isEmpty()
            && $this->optionValuesCount->isEmpty()
            && $this->optionCheckedCount->isEmpty()
        ) {
            return collect([]);
        }

        return $this->option->getOptionsByCategory(
            $this->optionsCount->keys()->merge($this->optionCheckedCount->keys())->toArray(),
            $this->getFiltersCategories(),
            $this->isFilterAutoranking
        )
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
        $optionSettingId = $option['option_setting_id'];

        return collect([
            'option_id' => $option['option_id'],
            'option_name' => $option['option_name'],
            'option_title' => $this->optionSettingTranslations[$optionSettingId]['option_title']
                ?? $this->optionTranslations[$option['option_id']]['title'] ?? '',
            'option_type' => $option['type'],
            'title_accusative' => $this->optionSettingTranslations[$optionSettingId]['title_accusative'] ?? null,
            'title_genetive' => $this->optionSettingTranslations[$optionSettingId]['title_genetive'] ?? '',
            'title_prepositional' => $this->optionSettingTranslations[$optionSettingId]['title_prepositional'] ?? null,
            'special_combobox_view' => $option['special_combobox_view'],
            'comparable' => $option['comparable'],
            'hide_block' => !!$option['hide_block'],
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
            'option_value_title' => strip_tags($this->optionValueTranslations[$optionValueId]['title'] ?? ''),
            'title_genetive' => $this->optionValueTranslations[$optionValueId]['title_genetive'] ?? '',
            'title_accusative' => $this->optionValueTranslations[$optionValueId]['title_accusative'] ?? '',
            'title_prepositional' => $this->optionValueTranslations[$optionValueId]['title_prepositional'] ?? '',
            'color_hash' => $option['option_value_color'],
            'is_chosen' => false,
            'products_quantity' => $this->optionValuesCount->get($optionValueId, 0),
            'is_value_show' => !!$option['is_value_show']
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
        $optionSettingId = $option['option_setting_id'];

        return collect([
            'option_value_id' => $optionId,
            'option_value_name' => OptionChecked::FILTER_CHECKBOX_VALUE,
            'option_value_title' => $this->optionSettingTranslations[$optionSettingId]['option_title']
                ?? $this->optionTranslations[$option['option_id']]['title'] ?? '',
            'title_accusative' => $this->optionSettingTranslations[$optionSettingId]['title_accusative'] ?? '',
            'title_genetive' => $this->optionSettingTranslations[$optionSettingId]['title_genetive'] ?? '',
            'title_prepositional' => $this->optionSettingTranslations[$optionSettingId]['title_prepositional'] ?? '',
            'color_hash' => null,
            'is_chosen' => false,
            'products_quantity' => $this->optionCheckedCount->get($optionId, 0),
            'is_value_show' => !!$option['is_value_show']
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

        $sortedValues = $this->getSortedValues($option['option_values']->toArray());

        $option->put('total_found', $sortedValues['total_found']);
        $option->put('option_values', array_merge($sortedValues['short_list'], $sortedValues['option_values']));

        return $option;
    }


}
