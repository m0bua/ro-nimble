<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\Brands\SearchBrands;
use App\Modules\FiltersModule\FiltersService;

class SearchBrandsController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/v1/search/brands",
     *     summary="Поиск брендов",
     *     description="Возвращает список брендов для товарной выдачи",
     *
     *     @OA\Parameter (
     *          name="category_id",
     *          in="query",
     *          required=true,
     *          description="Текущая категория",
     *          @OA\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="query",
     *          in="query",
     *          required=true,
     *          description="Часть названия бренда",
     *          @OA\Schema (
     *              type="string"
     *          )
     *     ),
     *
     *     @OA\Response (
     *          response=200,
     *          description="Успешный ответ"
     *     )
     * )
     *
     * @param FiltersService $filtersService
     * @return SearchBrands
     */
    public function index(FiltersService $filtersService): SearchBrands
    {
        return SearchBrands::make($filtersService->searchBrands());
    }
}
