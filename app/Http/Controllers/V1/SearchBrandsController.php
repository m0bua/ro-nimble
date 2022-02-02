<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\Brands\SearchBrands;
use App\Modules\FiltersModule\FiltersService;

class SearchBrandsController extends Controller
{
    /**
     * @param FiltersService $filtersService
     * @return SearchBrands
     */
    public function index(FiltersService $filtersService): SearchBrands
    {
        return SearchBrands::make($filtersService->searchBrands());
    }
}
