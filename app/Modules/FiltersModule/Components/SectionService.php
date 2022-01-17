<?php
/**
 * Класс для создания кастомного фильтра "Дерево категорий"
 * Class SectionService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Elastic;
use App\Enums\Filters;
use App\Models\Eloquent\Category as CategoryModel;

class SectionService extends BaseComponent
{
    private array $filterCategories = [];

    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->sectionFilterComponent->getValue();
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        if ($this->filters->promotion->getValues()->isEmpty()) {
            return [];
        }

        $this->filters->section->hideValues();

        $aggregateCategories = $this->elasticWrapper->prepareAggrCompositeData(
            $this->getData(),
            Elastic::FIELD_CATEGORIES_PATH
        );

        $this->filters->section->showValues();

        if (!$aggregateCategories) {
            return [];
        }

        $categories = $this->getCategories(array_keys($aggregateCategories));

        if (!$categories) {
            return [];
        }

        $chosenCategory = [];

        if ($this->filters->section->getValues()->isNotEmpty()) {
            $chosenCategoryKey = array_search(
                $this->filters->section->getValues()->first(),
                array_column($categories, 'id')
            );

            if ($chosenCategoryKey !== false) {
                $chosenCategory = $categories[$chosenCategoryKey];

                $this->setChosen($chosenCategory);
            }
        }

        $parentIds = array_unique(array_column($categories, 'parent_id'));

        // если товары акции относятся только к fashion
        $onlyFashion = !$chosenCategory
            && in_array($this->filters->category::FASHION_CATEGORY_ID, $parentIds)
            && count(array_keys(array_column($categories, 'parent_id'), 0)) == 1;

        $visibleCategories = $this->getVisibleCategories(
            $categories,
            0,
            1,
            $aggregateCategories,
            $this->filters->options->getOptionCategories(),
            $onlyFashion,
            $parentIds
        );

        return [
            Filters::PARAM_SECTION => [
                'option_id' => Filters::PARAM_SECTION,
                'option_name' => Filters::PARAM_SECTION,
                'option_title' => __('filters.section'),
                'current' => $chosenCategory,
                'visible_tree' => $visibleCategories,
                'total_quantity' => array_sum(array_column($visibleCategories, 'count')),
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_TREE,
                'comparable' => Filters::COMPARABLE_MAIN
        ]];
    }

    /**
     * Построение дерева категорий
     * @param array $categories
     * @param int $parent
     * @param array $chosenCategory
     * @param bool $onlyFashion
     * @return array
     */
    public function getVisibleCategories(
        &$categories,
        $parent,
        $level,
        $aggregateCategories,
        $chosenCategories,
        $onlyFashion,
        $parents
    ): array {
        $result = [];
        $selectedKey = null;

        foreach ($categories as $key => $category) {
            if ($category['parent_id'] == $parent) {
                if ($chosenCategories && in_array($category['id'], $chosenCategories)) {
                    $category['children'] = $this->getVisibleCategories(
                        $categories,
                        $category['id'],
                        $level + 1,
                        $aggregateCategories,
                        $chosenCategories,
                        $onlyFashion,
                        $parents
                    );

                    $category['show_cat'] = 1;
                    $selectedKey = $key;
                } else {
                    $category['show_cat'] = 0;
                    $category['children'] = [];
                }

                $category['count'] = $parent != 0 && in_array($category['id'], $parents) ? null : $aggregateCategories[$category['id']];

                $category['level'] = $level;
                $result[$key] = $category;
            }
        }

        if ($selectedKey !== null && $level > 1) {
            $selectedCategory = $result[$selectedKey];
            unset($result[$selectedKey]);

            if (array_sum(array_column($selectedCategory['children'], 'show_cat'))) {
                $children = $selectedCategory['children'];
                $selectedCategory['children'] = [];
                $result = array_merge([$selectedCategory], $children);
            } else {
                $result = array_merge([$selectedCategory], $result);
            }
        }

        if ($onlyFashion) {
            $countLevelCategories = count($result);

            if ($countLevelCategories == 1) {
                $currentCategory = array_shift($result);

                $children = $this->getVisibleCategories(
                    $categories,
                    $currentCategory['id'],
                    $level + 1,
                    $aggregateCategories,
                    $chosenCategories,
                    $onlyFashion,
                    $parents
                );

                if ($level > 1) {
                    if (count($children) > 1) {
                        $currentCategory['children'] = $children;
                        $result = [$currentCategory];

                        $this->filters->options->setOptionCategories(array_column($children, 'id'));
                    } else {
                        $result = array_merge([$currentCategory], $children);
                        $this->filters->options->addOptionCategories(array_column($children, 'id'));
                    }
                } else {
                    $currentCategory['children'] = $children;
                    $result[] = $currentCategory;
                    $this->filters->options->addOptionCategories([$currentCategory['id']]);
                }
            }
        }

        // если нет выбранной категории, то динамические фильтра будут только для раскрытых категорий
        if ($parent == 0 && !$chosenCategories && !$onlyFashion && $result) {
            $this->filters->options->setOptionCategories(array_column($result, 'id'));
        }

        return array_values($result);
    }

    /**
     * Установка выбранной категории и дополнительных параметров для выбора опций
     * @param $chosenCategory
     */
    public function setChosen($chosenCategory): void
    {
        $this->filters->options->setOptionCategories(
            array_values(array_filter(
                explode('.', sprintf('%s.%s.', $chosenCategory['mpath'], $chosenCategory['id']))
        )));

        $this->chosen[Filters::PARAM_SECTION][$chosenCategory['id']] = [
            'id' => $chosenCategory['id'],
            'name' => $chosenCategory['id'],
            'option_title' => __('filters.section'),
            'option_value_title' => $chosenCategory['title'],
            'comparable' => Filters::COMPARABLE_MAIN,
        ];
    }

    /**
     * @param array $ids
     * @return array
     */
    public function getCategories(array $ids): array
    {
        $categories = CategoryModel::getByIds($ids, $this->getCategoriesFields());

        foreach ($categories as &$category) {
            unset($category['translations']);
        }

        return $categories;
    }

    /**
     * @return string[]
     */
    public function getCategoriesFields(): array
    {
        return [
            'id',
            'title',
            'name',
            'status',
            'mpath',
            'parent_id',
            'level',
        ];
    }
}
