<?php

namespace App\Http\Controllers;

use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphGoodsModel;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods(GraphGoodsModel $graphGoodsModel, ElasticGoodsModel $elasticGoodsModel)
    {
        $id = 200775625;
        $id = 97653;
        $id = 198516121;

        $goods = $graphGoodsModel->getOneById($id);

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
