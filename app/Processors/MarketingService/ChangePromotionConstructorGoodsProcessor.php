<?php

namespace App\Processors\MarketingService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsOneModel;
use App\Processors\AbstractCore;
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
        $elasticGoodsModel = new ElasticGoodsModel();
        $goodsOneModel = new GoodsOneModel();

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

        $currentData = $elasticGoodsModel->searchById($goodsId);
        $elasticGoodsModel->load($currentData);

        $promotionConstructor = new PromotionConstructor(
            [
                'id' => $constructorId,
                'promotion_id' => $promotionId,
                'gift_id' => $giftId,
            ]
        );
        $promotionConstructors = $elasticGoodsModel->getPromotionConstructors();
        $promotionConstructor->setSeats($promotionConstructors);

        $emptySeat = $promotionConstructor->takeEmptySeat();
        $elasticGoodsModel->setPromotionConstructors($emptySeat);

        $goodsOneData = $goodsOneModel->getDefaultDataById($goodsId);

        $commonFormatter = new CommonFormatter($goodsOneData);
        $commonFormatter->formatGoodsForIndex();
        $commonFormatter->formatOptionsForIndex();
        $formattedData = $commonFormatter->getFormattedData();

        $elasticGoodsModel->load($formattedData)->index();

        unset($gqGoodsModel, $elasticGoodsModel, $message);

        return Processor::CODE_SUCCESS;
    }
}
