<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Options;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;

class ChangePromotionConstructorGroupProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        $options = new Options();
        $gqGoodsModel = new GraphQLGoodsModel($options);
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

        return Processor::CODE_SUCCESS;
    }
}