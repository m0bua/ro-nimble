<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GoodsDetailsResource;
use App\Http\Resources\GoodsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
     * @return GoodsResource
     */
    public function index(): GoodsResource
    {
        $data = [
            'ids' => [1, 2, 3, 4, 5]
        ];

        return GoodsResource::make($data);
    }
}
