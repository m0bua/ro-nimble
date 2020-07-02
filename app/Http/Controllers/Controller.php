<?php

namespace App\Http\Controllers;

use App\Models\Elastic\Promotions\GoodsModel;
use App\Library\Services\GoodsService;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods(GoodsService $goodsService, GoodsModel $elasticGoodsModel)
    {
        $id = 200775625;
        $id = 97653;
        $id = 198516121;

        $goods = $goodsService->getById($id);

        dump(
            $goods
        );
        die;

        $response = $elasticGoodsModel->save($goods);
        dump(
            $response
        );
        die;

        $search = $elasticGoodsModel->searchById($id);

        dump(
            $search
        );
        die;

        return $response;
    }
}
