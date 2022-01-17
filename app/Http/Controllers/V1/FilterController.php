<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FilterResource\FilterResource;
use App\Modules\FiltersModule\FiltersService;

class FilterController extends Controller
{
    /**
     * @OA\Get (
     *     path="/api/v1/filters",
     *     summary="Список фильтров",
     *     description="Возвращает список фильтров для товарной выдачи",
     *
     *     @OA\Parameter (
     *          name="country",
     *          in="query",
     *          required=false,
     *          description="Параметр страны",
     *          @OA\Schema (
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="lang",
     *          in="query",
     *          required=false,
     *          description="Параметр языка",
     *          @OA\Schema (
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="category_id",
     *          in="query",
     *          required=false,
     *          description="Текущая категория",
     *          @OA\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="promotion_id",
     *          in="query",
     *          required=false,
     *          description="Текущая акция",
     *          @OA\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="section_id",
     *          in="query",
     *          required=false,
     *          description="Текущая секция(категория)",
     *          @OA\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="categories",
     *          in="query",
     *          required=false,
     *          description="Список категорий",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="producer",
     *          in="query",
     *          required=false,
     *          description="Список производителей",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="series",
     *          in="query",
     *          required=false,
     *          description="Выбор серии производителя",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="price",
     *          in="query",
     *          required=false,
     *          description="Диапазон цены",
     *          @OA\Schema (
     *              type="string",
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="seller",
     *          in="query",
     *          required=false,
     *          description="Список продавцов",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="with_bonus",
     *          in="query",
     *          required=false,
     *          description="Товары с бонусами",
     *          @OA\Schema (
     *              type="string",
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="state",
     *          in="query",
     *          required=false,
     *          description="Выбор товаром Новый - Б/у",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="single_goods",
     *          in="query",
     *          required=false,
     *          description="Группировка товаров",
     *          @OA\Schema (
     *              type="string",
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="goods_with_promotions",
     *          in="query",
     *          required=false,
     *          description="Параметры фильтра Товары с акциями",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Parameter (
     *          name="sell_status",
     *          in="query",
     *          required=false,
     *          description="Статусы товаров",
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @OA\Response (
     *          response=200,
     *          description="Успешный ответ"
     *     )
     * )
     *
     * @param FiltersService $filtersService
     * @return FilterResource
     */
    public function index(FiltersService $filtersService): FilterResource
    {
        return FilterResource::make($filtersService->getFilters());
    }
}
