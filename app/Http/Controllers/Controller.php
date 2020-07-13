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
        $id = 199807069;
        $id = 108521;

        $goods = $graphGoodsModel->getOneById($id);

        $elasticGoodsModel->load(array_merge([
            'promotion_id' => 222,
            'constructor_id' => 333,
            'gift_id' => 444,
        ], $goods));

        $elasticGoodsModel->setPromotionId(123);die;
        dump($elasticGoodsModel->promotionId);die;

//        $response = $elasticGoodsModel->save();
//        dump(
//            $response
//        );
//        die;

        $search = $elasticGoodsModel->searchById($id);

        dump(
            $search['hits']['hits'][0]['_source'],
            array_merge([
                'promotion_id' => 222,
                'constructor_id' => 333,
                'gift_id' => 444,
            ], $goods)
        );
        die;

        return $response;
    }
}
