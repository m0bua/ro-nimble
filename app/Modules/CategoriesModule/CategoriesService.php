<?php
/**
 * Class CategoriesService
 * @package App\Modules\CategoriesModule
 */

namespace App\Modules\CategoriesModule;

use App\Filters\Filters;
use App\Modules\FiltersModule\Components\CategoriesService as FiltersCategoriesService;

class CategoriesService
{
    /**
     * @var Filters
     */
    private Filters $filters;

    /**
     * @var FiltersCategoriesService
     */
    private FiltersCategoriesService $categoriesService;

    public function __construct(
        Filters $filters,
        FiltersCategoriesService $categoriesService
    ) {
        $this->filters = $filters;
        $this->categoriesService = $categoriesService;
    }

    /**
     * Возвращает дынные о категории и ее подкатегориях
     * @return array
     */
    public function getCategoryData(): array
    {
        return $this->categoriesService->getCategoryData();
    }
}
