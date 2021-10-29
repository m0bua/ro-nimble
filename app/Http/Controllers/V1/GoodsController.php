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

    /**
     * @OA\Get (
     *     path="/api/v1/goods/details",
     *     summary="Детали по товарам",
     *     description="Возвращает дополнительную информацию по ID товарам",
     *     @OA\Parameter (
     *          name="ids",
     *          in="query",
     *          required=true,
     *          @OA\Schema (
     *              type="array",
     *              @OA\Items (
     *                  type="integer"
     *              )
     *          )
     *     ),
     *     @OA\Response (
     *          response=200,
     *          description="Успешный ответ"
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function details(Request $request)
    {
        $goodsIds = $request->get('ids', []);

        // ### Временная заглушка ###
        $data = [];
        array_map(function ($id) use (&$data) {
            $data[] = [
                'id' => $id,
                'bonuses' => [
                    'bonus_charge_pcs' => rand(0, 100),
                    'use_instant_bonus' => rand(0, 100),
                    'premium_bonus_charge_pcs' => rand(0, 100),
                ],
                'payment_methods' => [
                    [
                        'id' => rand(1, 100),
                        'parent_id' => rand(1, 100),
                        'name' => Str::random(5),
                        'order' => rand(0, 10),
                        'status' => Arr::random(['active', 'locked', null])
                    ],
                    [
                        'id' => rand(1, 100),
                        'parent_id' => rand(1, 100),
                        'name' => Str::random(5),
                        'order' => rand(0, 10),
                        'status' => Arr::random(['active', 'locked', null])
                    ],
                    [
                        'id' => rand(1, 100),
                        'parent_id' => rand(1, 100),
                        'name' => Str::random(5),
                        'order' => rand(0, 10),
                        'status' => Arr::random(['active', 'locked', null])
                    ],
                ],
            ];
        }, $goodsIds);
        // ###

        return GoodsDetailsResource::collection($data);
    }
}
