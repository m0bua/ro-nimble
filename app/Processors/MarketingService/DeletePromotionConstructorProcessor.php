<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;

class DeletePromotionConstructorProcessor extends AbstractCore
{
    /**
     * @throws Exception
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $constructorId = $this->message->getField('fields_data.id');
        $goodsByConstructor = $elasticGoodsModel->searchTermByField('promotion_constructors.id', $constructorId);

        if (!empty($goodsByConstructor)) {
            array_map(function ($goodsData) use ($constructorId, $elasticGoodsModel) {
                $elasticGoodsModel->load($goodsData);
                $elasticGoodsModel->set_promotion_constructors(
                    PromotionConstructor::remove($constructorId, $elasticGoodsModel->get_promotion_constructors())
                );
                $elasticGoodsModel->index();
            }, $elasticGoodsModel->all($goodsByConstructor));
        }

        app('redis')->unlink($constructorId);

        return Processor::CODE_SUCCESS;
    }
}
