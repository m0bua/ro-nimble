<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoodsResource\GoodsResource;
use App\Modules\GoodsModule\GoodsService;

class GoodsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/goods",
     *     summary="Получение списка ID товаров",
     *     description="Производит подбор, фильтрацию и сортировку товаров по входящим параметрам (фильтрам)",
     *     operationId="index",
     *     deprecated=false,
     *
     *     @OA\Parameter(ref="#/components/parameters/country"),
     *     @OA\Parameter(ref="#/components/parameters/category_id"),
     *     @OA\Parameter(ref="#/components/parameters/promotion_id"),
     *     @OA\Parameter(ref="#/components/parameters/section_id"),
     *     @OA\Parameter(ref="#/components/parameters/categories"),
     *     @OA\Parameter(ref="#/components/parameters/producers"),
     *     @OA\Parameter(ref="#/components/parameters/series"),
     *     @OA\Parameter(ref="#/components/parameters/price"),
     *     @OA\Parameter(ref="#/components/parameters/sellers"),
     *     @OA\Parameter(ref="#/components/parameters/with_bonus"),
     *     @OA\Parameter(ref="#/components/parameters/states"),
     *     @OA\Parameter(ref="#/components/parameters/payments"),
     *     @OA\Parameter(ref="#/components/parameters/single_goods"),
     *     @OA\Parameter(ref="#/components/parameters/goods_with_promotions"),
     *     @OA\Parameter(ref="#/components/parameters/sort"),
     *     @OA\Parameter(ref="#/components/parameters/page"),
     *     @OA\Parameter(ref="#/components/parameters/per_page"),
     *     @OA\Parameter(ref="#/components/parameters/query"),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={"data":{
     *                         "ids": {
     *                             282286938,
     *                             344108557
     *                         },
     *                         "ids_count": 10000,
     *                         "goods_in_category": 10000,
     *                         "shown_page": 1,
     *                         "goods_limit": 60,
     *                         "total_pages": 167
     *                     }}
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={"code":"400", "error": "Bad Request", "messages": {"Missing required parameters"}}
     *                 )
     *             )
     *         }
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal Server Error",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={"code":"500", "error": "Internal Server Error", "messages": {}}
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @param GoodsService $goodsService
     * @return GoodsResource
     */
    public function index(GoodsService $goodsService): GoodsResource
    {
        return GoodsResource::make($goodsService->getGoods());
    }
}
