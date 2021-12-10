<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoodsDetailsRequest;
use App\Models\Eloquent\Goods;
use Exception;
use Illuminate\Http\Request;

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
     * @return array
     */
    public function index(GoodsDetailsRequest $request, Goods $goodsModel): array
    {
        $goodsIds = $request->get('ids', []);
        $data =  [];
        $goodsCollection = $goodsModel->getGoodsDetails($goodsIds);

        array_map(function ($goods) use (&$data) {
            $bonuses = json_decode($goods['bonuses'], true);
            $data[] = [
                'id' => $goods['id'],
                'bonuses' => is_null($bonuses)
                || (
                    empty($bonuses['bonus_charge_pcs'])
                    && empty($bonuses['use_instant_bonus'])
                    && empty($bonuses['premium_bonus_charge_pcs'])
                ) ? null : $bonuses,
                'payment_methods' => json_decode($goods['payment_methods'], true) ?? []
            ];
        }, $goodsCollection);

        return $data;
    }
}
