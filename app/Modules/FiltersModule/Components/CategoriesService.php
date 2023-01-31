<?php
/**
 * Класс для создания кастомного фильтра "Категории товаров"
 * Class CategoriesService
 * @package App\Modules\FiltersModule\Components
 */

namespace App\Modules\FiltersModule\Components;

use App\Enums\Filters;
use App\Helpers\UrlHelper;
use App\Models\Eloquent\Category;

class CategoriesService extends BaseComponent
{
    /**
     * @return array
     */
    public function getFilterQuery(): array
    {
        return $this->categoriesFilterComponent->getValue();
    }

    /**
     * @inerhitDoc
     * @return array
     */
    public function getQuery(): array
    {
        if (!$this->filters->category->isFashion()) {
            return [];
        }
        $this->filters->categories->hideValues();
        $query = $this->getDataQuery();

        $this->filters->categories->showValues();
        return [$this->categoriesFilterComponent::AGGR_CATEGORIES => $query];
    }

    /**
     * @inerhitDoc
     * @param array $response
     * @return array
     */
    public function getValueFromMSearch(array $response): array
    {
        if (!$this->filters->category->isFashion()) {
            return [];
        }

        $this->filters->categories->hideValues();

        $childCategories = $this->getCategoryWithChildren($response);

        $this->filters->categories->showValues();


        if (!$childCategories['children']) {
            return [];
        }

        $childCategories = $childCategories['children'];

        $categories = [];
        $order = 0;

        foreach ($childCategories as $childCategory) {
            $category = [
                'option_value_id' => $childCategory['id'],
                'option_value_name' => $childCategory['name'],
                'option_value_title' => $childCategory['title'],
                'is_chosen' => false,
                'products_quantity' => $childCategory['count'],
                'order' => $order,
            ];

            $order++;

            // установка выбранных фильтров
            if ($this->filters->categories->getValues()->contains($childCategory['id'])) {
                $category['is_chosen'] = true;

                $this->chosen[Filters::PARAM_CATEGORIES][$category['option_value_name']] = [
                    'id' => $category['option_value_id'],
                    'name' => $category['option_value_name'],
                    'option_title' => __('filters.' . Filters::PARAM_CATEGORIES),
                    'option_value_title' => $category['option_value_title'],
                    'comparable' => Filters::COMPARABLE_MAIN,
                ];
            }

            $categories[] = $category;
        }

        return [
            Filters::PARAM_CATEGORIES => [
                'option_id' => Filters::PARAM_CATEGORIES,
                'option_name' => Filters::PARAM_CATEGORIES,
                'option_title' => __('filters.' . Filters::PARAM_CATEGORIES),
                'option_type' => Filters::OPTION_TYPE_COMBOBOX,
                'special_combobox_view' => Filters::SPECIAL_COMBOBOX_VIEW_LIST,
                'comparable' => Filters::COMPARABLE_MAIN,
                'hide_block' => false,
                'total_found' => count($categories),
                'option_values' => $categories
            ]
        ];
    }

    /**
     * @return array
     */
    public function getCategoryData(): array
    {
        $this->filters->categories->hideValues();

        $categoryWithChildren = $this->getCategoryWithChildren();

        $this->filters->categories->showValues();

        return $categoryWithChildren;
    }

    /**
     * Возвращает список дочерних категорий
     * @param array $response
     * @return array
     */
    public function getCategoryWithChildren(array $response): array
    {
        /** @var Category $category */
        $category = $this->filters->category->getCategory();

        $countCategories = $this->elasticWrapper->prepareAggrData(
            $response,
            $this->categoriesFilterComponent::AGGR_CATEGORIES
        );

        if (!$countCategories) {
            return [
                'current' => $category->toArray(),
                'children' => []
            ];
        }

        $childCategories = Category::getChildCategories($category);
        $category = $category->toArray();
        $directChildren = [];
        $allChildren = [];

        $category['goods_count'] = $countCategories[$category['id']] ?? 0;

        if ($childCategories) {
            /** @var Category $childCategory */
            foreach ($childCategories as $childCategory) {
                $id = $childCategory->id;
                $count = $countCategories[$id] ?? 0;

                $allChildren[$id] = $childCategory->toArray();
                $allChildren[$id]['count'] = $count;
                $allChildren[$id]['href'] = UrlHelper::changeDomain($childCategory->href);

                // к-во товаров самой категории
                $allChildren[$id]['goods_count'] = $count;
                // к-во не пустых подкатегорий
                $allChildren[$id]['count_children'] = 0;
                // id одной из подкатегорий
                $allChildren[$id]['child_id'] = 0;
            }

            foreach ($allChildren as $childCategoryId => $childCategory) {
                $count = $allChildren[$childCategoryId]['count'];
                $parentId = $childCategory['parent_id'];

                if (isset($allChildren[$parentId]) && $parentId != $category['id']) {
                    if ($count) {
                        $allChildren[$parentId]['count'] += $count;
                        $allChildren[$parentId]['count_children'] ++;
                        $allChildren[$parentId]['child_id'] = $childCategoryId;
                    }
                } elseif ($parentId == $category['id'] && $count > 0) {
                    $directChildren[] = $allChildren[$childCategoryId];
                }
            }

            $directChildren = array_reverse($directChildren);

            foreach ($directChildren as $catId => $directChild) {
                $directChildren[$catId] = $this->prepareCategory($directChild, $allChildren);
            }
        }

        return [
            'current' => $category,
            'children' => $directChildren
        ];
    }

    /**
     * Подготовка категории
     * @param array $category
     * @param array $categoriesList
     * @return array
     */
    public function prepareCategory(array $category, array $categoriesList): array
    {
        // если у категории отсутствуют свои товары и только одна подкатегория, подменяем ее
        if (!$category['goods_count']
            && $category['count_children'] == 1
            && !empty($category['child_id'])
            && !empty($categoriesList[$category['child_id']])
        ) {
            return $this->prepareCategory($categoriesList[$category['child_id']], $categoriesList);
        }

        return $category;
    }
}
