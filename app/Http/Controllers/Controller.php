<?php

namespace App\Http\Controllers;

use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphGoodsModel;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods(GraphGoodsModel $graphGoodsModel, ElasticGoodsModel $elasticGoodsModel)
    {
        $groupId = 24089947;
        $groupGoods = $graphGoodsModel->getManyByGroup($groupId);

        dump(
            $groupGoods
        );
        die;

//        if ($groupGoods) {
//            foreach ($groupGoods as $goods) {
//                $elasticGoodsModel->load(array_merge([
//                    'promotion_id' => 222,
//                    'constructor_id' => 333,
//                    'gift_id' => 444,
//                ], $goods));
//
//                $response = $elasticGoodsModel->index();
//            }
//        }
//        dump(
//            array_column($groupGoods, 'id')
//        );
//        die;

//        $search1 = $elasticGoodsModel->searchById(199807069);
//        $search2 = $elasticGoodsModel->searchById(199824781);
//
//        dump(
//            $search1['hits']['hits'][0]['_source'],
//            $search2['hits']['hits'][0]['_source']
//        );
//        die;

        $id = 200775625;
        $id = 97653;
        $id = 198516121;
        $id = 199807069;  //tags
        $id = 108521;
        $id = 208281043;
        $id = 151676890;
        $id = 96418468;
        $id = 17501340;
        $id = 155179;
        $id = 55981038;   //options
        $id = 112629260; //options
        $id = 183082;  //options with sliders
//        $id = 16631;

        $goods = $graphGoodsModel->getOneById($id);

        dump(
            $goods
        );
        die;

        $elasticGoodsModel->load(array_merge([
            'promotion_id' => 222,
            'constructor_id' => 333,
            'gift_id' => 444,
        ], $goods));

        $elasticGoodsModel->setPromotionId(123);

        $response = $elasticGoodsModel->index();
//        dump(
//            $response
//        );
//        die;

        $search = $elasticGoodsModel->searchById($id);

        dump(
            $search,
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
