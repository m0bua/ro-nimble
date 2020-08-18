<?php

namespace App\Processors;

use App\Models\Elastic\GoodsModel;
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
                $elasticGoodsModel->setPromotionConstructors(
                    PromotionConstructor::remove($constructorId, $elasticGoodsModel->getPromotionConstructors())
                );

                $elasticGoodsModel->index();
            }, $groupGoodsData);
        }

        return Processor::CODE_SUCCESS;
    }
}
