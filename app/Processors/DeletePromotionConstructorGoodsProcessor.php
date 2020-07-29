<?php

namespace App\Processors;

use App\Models\Elastic\Promotions\GoodsModel;
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
            $elasticGoodsModel->setPromotionConstructors(
                PromotionConstructor::remove($constructorId, $elasticGoodsModel->getPromotionConstructors())
            );

            $elasticGoodsModel->index();
        }

        unset($elasticGoodsModel, $message);

        return Processor::CODE_SUCCESS;
    }
}
