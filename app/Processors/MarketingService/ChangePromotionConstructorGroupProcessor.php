<?php

namespace App\Processors\MarketingService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsManyModel;
use App\Processors\AbstractCore;
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
        $goodsManyModel = new GoodsManyModel();
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

        array_map(function ($goods) use ($elasticGoodsModel, $promotionConstructor) {
            $currentData = $elasticGoodsModel->one($elasticGoodsModel->searchById($goods['id']));
            $elasticGoodsModel->load($currentData);

            $promotionConstructors = $elasticGoodsModel->get_promotion_constructors();
            $promotionConstructor->setSeats($promotionConstructors);

            $emptySeat = $promotionConstructor->takeEmptySeat();
            $elasticGoodsModel->set_promotion_constructors($emptySeat);

            $commonFormatter = new CommonFormatter($goods);
            $commonFormatter->formatGoodsForIndex();
            $commonFormatter->formatOptionsForIndex();
            $formattedData = $commonFormatter->getFormattedData();

            $elasticGoodsModel->load($formattedData)->index();
        }, $goodsManyModel->getDefaultDataByGroupId($groupId));

        unset($goodsOneModel, $elasticGoodsModel);

        return Processor::CODE_SUCCESS;
    }
}
