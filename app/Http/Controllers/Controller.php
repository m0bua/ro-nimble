<?php

namespace App\Http\Controllers;

use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\ValueObjects\Options;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function goods()
    {
        $options = new Options();
        $graphGoodsModel = new GraphQLGoodsModel($options);
        $elasticGoodsModel = new ElasticGoodsModel();
//        $groupId = 24089947;
//        $groupGoods = $graphGoodsModel->getManyByGroup($groupId);
//
//        dump(
//            $groupGoods
//        );
//        die;
//
//        if ($groupGoods) {
//            foreach ($groupGoods as $goods) {
//                $elasticGoodsModel->load(array_merge([
//                    'promotion_id' => 222,
//                    'constructor_id' => 333,
//                    'gift_id' => 444,
//                ], $goods));

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
        $id = 102190;
        $id = 108521;
        $id = 196475245;
        $id = 208281043;
        $id = 151676890;
        $id = 96418468;
        $id = 17501340;
        $id = 155179;
        $id = 55981038;   //options with checked
//        $id = 112629260; //options with checked    empty
        $id = 183082;  //options with sliders
        $id = 16631;  //options with sliders and with checked
//        $id = 229495;
//        $id = 229494;

        $goods = $graphGoodsModel->getOneById($id);
//        dump(
//            $goods
//        );
//        die;

        $elasticGoodsModel->load(array_merge([
            'promotion_constructors' => [
                [
                    'id' => 1,
                    'promotion_id' => 2,
                    'gift_id' => 3,
                ],
                [
                    'id' => 11,
                    'promotion_id' => 22,
                    'gift_id' => 33,
                ],
                [
                    'id' => 111,
                    'promotion_id' => 222,
                    'gift_id' => 333,
                ],
            ]
        ], $goods));

//        $elasticGoodsModel->load(array_merge([
//            'promotion_constructors' => [[
//                'id' => 3333,
//                'promotion_id' => 2222,
//                'gift_id' => 444,
//        ]]], $goods));

//        $elasticGoodsModel->setPromotionId([123]);

        $response = $elasticGoodsModel->index();
//        dump(
//            $response
//        );
//        die;

        $search = $elasticGoodsModel->searchById($id);

        dump(
            $search
//            ,
//            $search['hits']['hits'][0]['_source'],
//            array_merge([
//                'promotion_id' => 222,
//                'constructor_id' => 333,
//                'gift_id' => 444,
//            ], $goods)
        );
        die;

        return $response;
    }
}
