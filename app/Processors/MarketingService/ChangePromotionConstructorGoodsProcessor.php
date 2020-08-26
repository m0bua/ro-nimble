<?php

namespace App\Processors\MarketingService;

use App\Models\GraphQL\GoodsModel as GraphQLGoodsModel;
use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Options;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;
use ReflectionException;

class ChangePromotionConstructorGoodsProcessor extends AbstractCore
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function doJob()
    {
        $options = new Options();
        $gqGoodsModel = new GraphQLGoodsModel($options);
        $elasticGoodsModel = new ElasticGoodsModel();

        $giftId = null;
        $promotionId = null;
        $goodsId = $this->message->getField('fields_data.goods_id');
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

        $elasticGoodsModel->load($elasticGoodsModel->searchById($goodsId));
        $promotionConstructor->setSeats($elasticGoodsModel->getPromotionConstructors());
        $elasticGoodsModel->setPromotionConstructors($promotionConstructor->takeEmptySeat());
        $elasticGoodsModel->load($gqGoodsModel->getOneById($goodsId))->index();

        unset($gqGoodsModel, $elasticGoodsModel, $message);

        return Processor::CODE_SUCCESS;
    }
}