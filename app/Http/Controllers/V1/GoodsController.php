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
     *     @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
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
