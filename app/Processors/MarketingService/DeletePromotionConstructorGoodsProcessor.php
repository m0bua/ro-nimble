<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use ReflectionException;

class DeletePromotionConstructorGoodsProcessor extends AbstractCore
{
    /**
     * @throws ReflectionException
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();

        $goodsData = $elasticGoodsModel->searchById(
            $this->message->getField('fields_data.goods_id')
        );

        if (!empty($goodsData)) {
            $constructorId = $this->message->getField('fields_data.promotion_constructor_id');

            $elasticGoodsModel->load($goodsData);
            $elasticGoodsModel->set_promotion_constructors(
                PromotionConstructor::remove($constructorId, $elasticGoodsModel->get_promotion_constructors())
            );

            $elasticGoodsModel->index();
        }

        unset($elasticGoodsModel, $message);

        return Processor::CODE_SUCCESS;
    }
}
