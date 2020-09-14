<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\OptionOneModel;
use App\Processors\AbstractCore;

class ChangeGoodsOptionProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
//        $goodsId = $this->message->getField('data.goods_id');
//        $optionId = $this->message->getField('data.option_id');
//        $optionType = $this->message->getField('data.type');
//        $optionValue = $this->message->getField('data.value');
//
//        $elasticGoodsModel = new GoodsModel();
//        $optionOneModel = new OptionOneModel();
//        $optionOne = $optionOneModel->setSelectionSet(['name', 'state'])->getById($optionId);
//
//        $goodsData = [
//            'id' => $goodsId,
//            'options' => [
//                'details' => [
//                    'id' => $optionId,
//                    'name' => $optionOne['name'],
//                    'type' => $optionType,
//                    'state' => $optionOne['state'],
//                ],
//                'value' => $optionValue
//            ]
//        ];
//
//        $formatter = new CommonFormatter($goodsData);
//        $formatter->formatGoodsForIndex();
//        $formatter->formatOptionsForIndex();
//        $formattedData = $formatter->getFormattedData();
//        $currentData = $elasticGoodsModel->searchById($goodsId);
//        $elasticGoodsModel->load(array_merge($currentData, $formattedData));
//        $elasticGoodsModel->index();
    }
}
