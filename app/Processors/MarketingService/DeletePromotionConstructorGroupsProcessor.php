<?php

namespace App\Processors\MarketingService;

use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use App\ValueObjects\PromotionConstructor;
use Exception;

class DeletePromotionConstructorGroupsProcessor extends AbstractCore
{

    /**
     * @return mixed|void
     * @throws Exception
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();

        $groupGoodsData = $elasticGoodsModel->searchTermByField(
            'group_id',
            $this->message->getField('fields_data.group_id')
        );

        if (!empty($groupGoodsData)) {
            $constructorId = $this->message->getField('fields_data.promotion_constructor_id');

            array_map(function ($goodsOne) use ($constructorId, $elasticGoodsModel) {
                $elasticGoodsModel->load($goodsOne);
                $elasticGoodsModel->set_promotion_constructors(
                    PromotionConstructor::remove($constructorId, $elasticGoodsModel->get_promotion_constructors())
                );

                $elasticGoodsModel->index();
            }, $groupGoodsData);
        }

        return Processor::CODE_SUCCESS;
    }
}
