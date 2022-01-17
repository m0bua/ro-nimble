<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryGetChildrenResource\CategoryGetChildrenResource;
use App\Modules\CategoriesModule\CategoriesService;

class CategoryChildrenController extends Controller
{
    /**
     * @param CategoriesService $categoriesService
     * @return CategoryGetChildrenResource
     */
    public function index(CategoriesService $categoriesService): CategoryGetChildrenResource
    {
        return CategoryGetChildrenResource::make($categoriesService->getCategoryData());
    }
}
