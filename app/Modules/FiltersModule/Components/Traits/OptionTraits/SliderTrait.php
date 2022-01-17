<?php
namespace App\Modules\FiltersModule\Components\Traits\OptionTraits;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Filters\Components\Options\OptionSliders;
use App\Models\Eloquent\Option;
use Illuminate\Support\Collection;

trait SliderTrait
{
    /**
     * Получаем значения слайдеров
     * @return Collection
     */
    public function getSliders(): Collection
    {
        if ($this->optionSlidersCount->isEmpty()) {
            return collect([]);
        }

        return $this->option->getSliders($this->getFiltersCategories())
            ->filter(function (Collection $slider) {
                if (!$this->optionSlidersCount->has($slider['option_id'])) {
                    return false;
                }

                $slider->put('min_value', $this->optionSlidersCount[$slider['option_id']]['min']);
                $slider->put('max_value', $this->optionSlidersCount[$slider['option_id']]['max']);

                return $slider['min_value'] != $slider['max_value'];
            })
            ->values();
    }

    /**
     * Подготовка слайдера
     * @param Collection $option
     * @return Collection
     */
        public function prepareSliderOption(Collection $option): Collection
    {
        $isInt = $option['option_type'] === Option::TYPE_INTEGER;

        if ($isInt) {
            $option['min_value'] = (int) $option['min_value'];
            $option['max_value'] = (int) $option['max_value'];
        }

        $valuesPattern = $isInt
            ? sprintf('^%s\d+$', $this->isNegativeSlidersValues(
                $this->checkPattern('/^-\d+$/', $option)
            ))
            : sprintf('^%s\d+(\.{1}\d+)?$', $this->isNegativeSlidersValues(
                $this->checkPattern('/^-\d+(\.{1}\d+)?$/', $option)
            ));

        $chosenSlider = $this->filters->options->optionSliders->getValues()[$option['option_id']] ?? null;
        $chosenValueMin = $chosenSlider && $chosenSlider[OptionSliders::MIN_KEY] >= $option['min_value']
            ? $chosenSlider[OptionSliders::MIN_KEY] : $option['min_value'];
        $chosenValueMax = $chosenSlider && $chosenSlider[OptionSliders::MAX_KEY] <= $option['max_value']
            ? $chosenSlider[OptionSliders::MAX_KEY] : $option['max_value'];

        if ($chosenSlider) {
            $range = sprintf('%s - %s', $chosenValueMin, $chosenValueMax);
            $this->chosen[$option['option_name']]['range'] = [
                'id' => $option['option_id'],
                'name' => $range,
                'option_title' => $option['option_title'],
                'option_value_title' => $range,
                'comparable' => Filters::COMPARABLE_MAIN
            ];
        }

        return collect([
            'option_id' => $option['option_id'],
            'option_name' => $option['option_name'],
            'option_title' => $option['option_title'],
            'option_type' => $option['option_type'],
            'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_SLIDER,
            'config' => [
                'unit' => $option['unit'] ?? '',
                'values_pattern' => $valuesPattern
            ],
            'range_values' => [
                'min' => $option['min_value'],
                'max' => $option['max_value']
            ],
            'chosen_values' => [
                'min' => $chosenValueMin,
                'max' => $chosenValueMax
            ]
        ]);
    }

    /**
     * @param bool $isNegative
     * @return string
     */
    public function isNegativeSlidersValues(bool $isNegative): string
    {
        return $isNegative ? '-?' : '';
    }

    /**
     * @param string $pattern
     * @param Collection $option
     * @return bool
     */
    public function checkPattern(string $pattern, Collection $option): bool
    {
        return preg_match($pattern, (string) $option['min_value']) || preg_match($pattern, (string) $option['max_value']);
    }

    /**
     * @param array $data
     * @return array
     */
    public function prepareAggrSlidersData(array $data)
    {
        $result = [];

        $data = $data['aggregations'][Elastic::FIELD_OPTION_SLIDERS][Elastic::FIELD_OPTION_SLIDERS];

        if (empty($data['buckets']) || !is_array($data['buckets'])) {
            return $result;
        }

        foreach ($data['buckets'] as $bucket) {
            $result[$bucket['key']] = [
                'min' => $bucket['min']['value'],
                'max' => $bucket['max']['value']
            ];
        }

        return $result;
    }
}
