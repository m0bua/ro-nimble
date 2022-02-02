<?php
/**
 * Класс для создания динамических фильтров
 * Class OptionsService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Filters\Components\Options\OptionValues;
use App\Helpers\CountryHelper;
use App\Models\Eloquent\Option;
use App\Modules\FiltersModule\Components\Traits\OptionTraits\SliderTrait;
use App\Modules\FiltersModule\Components\Traits\OptionTraits\ValuesAndCheckedTrait;
use Illuminate\Support\Collection;

class OptionsService extends BaseComponent
{
    use ValuesAndCheckedTrait;
    use SliderTrait;

    /**
     * @var Collection
     */
    private $allOptions;
    /**
     * @var Collection
     */
    private $optionValuesCount;
    /**
     * @var Collection
     */
    private $optionCheckedCount;
    /**
     * @var Collection
     */
    private $optionSlidersCount;
    /**
     * @var Collection
     */
    private $chosenOptionValues;
    /**
     * @var Collection
     */
    private $chosenOptionChecked;

    /** @var array $currentFilterComponent */
    private array $currentFilterComponent = [];

    /**
     * @param array $filterComponent
     * @return void
     */
    private function setCurrentFilterComponent(array $filterComponent): void
    {
        $this->currentFilterComponent = $filterComponent;
    }

    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->currentFilterComponent ? $this->elasticWrapper->aggs($this->currentFilterComponent) : [];
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $this->aggrAllFiltersCount();

        $valuesAndChecked = $this->getValuesAndChecked();
        $sliders = $this->getSliders();

        if ($valuesAndChecked->isEmpty() && $sliders->isEmpty()) {
            return [];
        }

        $this->setAllOptions($valuesAndChecked->merge($sliders));

        if ($this->filters->isHasFilters()) {
            $this->aggrValuesAndCheckedCount();
            $this->updateValuesAndCheckedCount();
        }

        return $this->allOptions->each(function (Collection $option) {
            return $this->prepareFilter($option);
        })->values()->toArray();
    }

    /**
     * Формируем массив всех опций
     * @param Collection $options
     * @return void
     */
    public function setAllOptions(Collection $options)
    {
        $this->allOptions = collect([]);

        $options->each(function (Collection $option) {
            if ($this->checkIsTag($option)) {
                return true;
            }

            $optionId = $option['option_id'];

            /** Опция для слайдера */
            if ($option->has('max_value')) {
                $this->allOptions->put($optionId, $this->prepareSliderOption($option));

                return true;
            }

            if (!$this->allOptions->has($optionId)) {
                $this->allOptions->put($optionId, $this->prepareOption($option));
            }

            $optionValueId = $option['option_value_id'];

            if ($optionValueId !== null) {
                if ($this->allOptions->get($optionId)->has($optionValueId)) {
                    return true;
                }

                $this->allOptions->get($optionId)->get('option_values')
                    ->put($optionValueId, $this->prepareOptionValue($option, $optionValueId));
            } else {
                $this->allOptions->get($optionId)->get('option_values')
                    ->put($optionId, $this->prepareOptionChecked($option, $optionId));
            }
        });
    }

    /**
     * Обновление данных о количестве опций
     * @return void
     */
    public function updateValuesAndCheckedCount()
    {
        $this->allOptions->each(function (Collection $option) {
            if ($option->has('option_values')) {
                $isValues = $this->isOptionValues($option);
                $option['option_values']->each(function (Collection $optionValue) use ($option, $isValues) {
                    $optionValue['products_quantity'] = $isValues
                        ? $this->optionValuesCount->get($optionValue['option_value_id'], 0)
                        : $this->optionCheckedCount->get($option['option_id'], 0);
                });
            }
        });

        $chosenValues = $this->filters->options->optionValues->getValues();

        if ($chosenValues->isNotEmpty()) {
            $chosenValues->each(function (Collection $chosenOptionValue, $chosenOptionId) {
                if ($chosenOptionValue[$this->filters->options->optionValues::KEY_ADDITION]) {
                    $aggrValuesCount = $this->aggrValuesCount($chosenOptionId);

                    $this->allOptions->each(function (Collection $option) use ($chosenOptionId, $aggrValuesCount) {
                        if ($option['option_id'] == $chosenOptionId && $option->has('option_values')) {
                            $option['option_values']->each(function (Collection $optionValue) use ($aggrValuesCount) {
                                $optionValue['products_quantity'] = $aggrValuesCount->get($optionValue['option_value_id'], 0);
                            });
                        }
                    });
                }
            });
        }

        $this->chosenOptionValues = $this->filters->options->optionValues->getValues()->map(function ($option) {
            return $option[OptionValues::KEY_VALUES];
        })->collapse();

        $this->chosenOptionChecked = $this->filters->options->optionChecked->getValues();

        $this->allOptions->each(function (Collection $option, $optionKey) {
            if ($option->has('option_values')) {
                $isValues = $this->isOptionValues($option);
                $option['option_values']->each(function (Collection $optionValue, $optionValueKey) use ($option, $optionKey, $isValues) {
                    $isChosen = $isValues
                        ? $this->chosenOptionValues->contains($optionValue['option_value_id'])
                        : $this->chosenOptionChecked->contains($option['option_id']);

                    if ($isChosen) {
                        $this->chosen[$option['option_name']][$optionValue['option_value_name']] = [
                            'id' => $optionValue['option_value_id'],
                            'name' => $optionValue['option_value_name'],
                            'option_title' => $option['option_title'],
                            'option_value_title' => $optionValue['option_value_title'],
                            'comparable' => Filters::COMPARABLE_MAIN
                        ];

                        $optionValue['is_chosen'] = $isChosen;
                    } elseif (!$optionValue['products_quantity']) {
                        $this->allOptions[$optionKey]['option_values']->forget($optionValueKey);
                    }
                });

                if ($option['option_values']->isEmpty()) {
                    $this->allOptions->forget($optionKey);
                }
            }
        });
    }

    /**
     * Возвращает агрегированные данные по всем фильтрам без учета выбранных
     * @return void
     */
    public function aggrAllFiltersCount()
    {
        $this->filters->hideFilters();

        $this->setCurrentFilterComponent(array_merge(
            $this->optionValuesFilterComponent->getValue(),
            $this->optionCheckedFilterComponent->getValue(),
            $this->optionSlidersFilterComponent->getValue(),
        ));

        $data = $this->getData();

        $this->optionValuesCount = collect($this->elasticWrapper->prepareAggrCompositeData(
            $data, Elastic::FIELD_OPTION_VALUES
        ))->recursive();
        $this->optionCheckedCount = collect($this->elasticWrapper->prepareAggrCompositeData(
            $data, Elastic::FIELD_OPTION_CHECKED
        ))->recursive();
        $this->optionSlidersCount = collect($this->prepareAggrSlidersData(
            $data
        ))->recursive();

        $this->filters->showFilters();
    }

    /**
     * Возвращает агрегированные данные по обычным фильтрам и чекбоксам с учетом выбранных фильтров
     * @return void
     */
    public function aggrValuesAndCheckedCount()
    {
        $this->setCurrentFilterComponent(array_merge(
            $this->optionValuesFilterComponent->getValue(),
            $this->optionCheckedFilterComponent->getValue(),
        ));

        $data = $this->getData();

        $this->optionValuesCount = collect($this->elasticWrapper->prepareAggrCompositeData(
            $data, Elastic::FIELD_OPTION_VALUES
        ))->recursive();
        $this->optionCheckedCount = collect($this->elasticWrapper->prepareAggrCompositeData(
            $data, Elastic::FIELD_OPTION_CHECKED
        ))->recursive();
    }

    /**
     * Возвращает агрегированные данные по обычным фильтрам без учета определенного фильтра
     * @return Collection
     */
    public function aggrValuesCount(?int $optionId): Collection
    {
        $option = $this->filters->options->optionValues->getValues()[$optionId];
        $this->filters->options->optionValues->forgetValueItem($optionId);
        $this->setCurrentFilterComponent($this->optionValuesFilterComponent->getValue());
        $data = $this->getData();
        $this->filters->options->optionValues->putValueItem($optionId, $option);

        return collect($this->elasticWrapper->prepareAggrCompositeData(
            $data, Elastic::FIELD_OPTION_VALUES
        ))->recursive();
    }

    /**
     * Отдает список категорий в рамках которых будут выводиться фильтра
     * @return array
     */
    public function getFiltersCategories()
    {
        // если это каталог, есть категория
        if ($this->filters->category->getCategory()) {
            return [$this->filters->category->getCategory()->id];
        // на акциях может быть выбрана секция (фильтр дерево категорий)
        } elseif ($this->filters->section->getValues()->isNotEmpty()) {
            return $this->filters->section->getValues()->all();
        // корневые категории, товары которых есть в акции
        } else {
            return $this->filters->options->getOptionCategories();
        }
    }

    /**
     * Определяет тип опции
     * @param Collection $option
     * @return bool
     */
    public function isOptionValues(Collection $option): bool
    {
        return $option['option_type'] != Option::TYPE_CHECKBOX;
    }

    /**
     * Доп условия для фильтров тегов
     * https://sd.local/browse/IVV-4020
     * @param Collection $option
     * @return bool
     */
    public function checkIsTag(Collection $option): bool
    {
        return !CountryHelper::isUaCountry()
            && !empty($option['comparable'])
            && $option['comparable'] == Filters::COMPARABLE_BOTTOM;
    }
}