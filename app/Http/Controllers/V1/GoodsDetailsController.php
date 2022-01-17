<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsDetailsRequest;
use App\Http\Resources\GoodsDetailsResource\GoodsDetailsResource;
use App\Models\Eloquent\Goods;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GoodsDetailsController extends Controller
{
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
     * @param GoodsDetailsRequest $request
     * @param Goods $goodsModel
     * @return AnonymousResourceCollection
     */
    public function index(GoodsDetailsRequest $request, Goods $goodsModel): AnonymousResourceCollection
    {
        $goodsIds = $request->input('ids', []);

        $goods = $goodsModel::findManyWithBonusAndPayments($goodsIds)
            ->transform(static fn(Goods $item) => [
                'id' => $item->id,
                'bonuses' => $item->bonus ? $item->bonus->toArray() : [],
                'payment_methods' => $item->paymentMethods->toArray(),
            ]);

        return GoodsDetailsResource::collection($goods);
    }
}
