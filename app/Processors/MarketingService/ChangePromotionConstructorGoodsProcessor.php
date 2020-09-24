<?php

namespace App\Processors\MarketingService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel as ElasticGoodsModel;
use App\Models\GraphQL\GoodsOneModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;
use Illuminate\Support\Facades\DB;
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

        DB::table('promotion_goods_constructors')
            ->updateOrInsert([
                'constructor_id' => $constructorId,
                'goods_id' => $goodsId
            ], [
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

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

        $currentData = $elasticGoodsModel->one($elasticGoodsModel->searchById($goodsId));
        $elasticGoodsModel->load($currentData);

        $promotionConstructors = $elasticGoodsModel->get_promotion_constructors();
        $promotionConstructor->setSeats($promotionConstructors);

        $emptySeat = $promotionConstructor->takeEmptySeat();
        $elasticGoodsModel->set_promotion_constructors($emptySeat);

        $goodsOneData = $goodsOneModel->getDefaultDataById($goodsId);

        $commonFormatter = new CommonFormatter($goodsOneData);
        $commonFormatter->formatGoodsForIndex();
        $commonFormatter->formatOptionsForIndex();
        $formattedData = $commonFormatter->getFormattedData();

        $elasticGoodsModel->load($formattedData)->index();

        unset($goodsOneModel, $elasticGoodsModel);

        return Processor::CODE_SUCCESS;
    }
}
