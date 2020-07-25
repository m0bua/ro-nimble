<?php

namespace App\Processors;

use App\Models\Elastic\Promotions\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\ValueObjects\PromotionConstructor;
use Exception;

class ChangePromotionConstructorGroupProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        $gqGoodsModel = new GraphQLGoodsModel();
        $elasticGoodsModel = new ElasticGoodsModel();

        $giftId = null;
        $promotionId = null;
        $groupId = $this->message->getField('fields_data.group_id');
        $constructorId = $this->message->getField('fields_data.promotion_constructor_id');

        $constructorInfo = json_decode(
            app('redis')->get($constructorId)
        );

        if ($constructorInfo !== null) {
            $promotionId = $constructorInfo->promotion_id;
            $giftId = $constructorInfo->gift_id;
        }

        $promotionConstructor = new PromotionConstructor(
            [
                'id' => $constructorId,
                'promotion_id' => $promotionId,
                'gift_id' => $giftId,
            ]
        );

        array_map(function ($goods) use (
            $elasticGoodsModel,
            $promotionConstructor
        ) {
            $elasticGoodsModel->load($elasticGoodsModel->searchById($goods['id']));
            $promotionConstructor->setSeats($elasticGoodsModel->getPromotionConstructors());
            $elasticGoodsModel->setPromotionConstructors($promotionConstructor->takeEmptySeat());
            $elasticGoodsModel->load($goods)->index();

        }, $gqGoodsModel->getManyByGroup($groupId));
    }
}
