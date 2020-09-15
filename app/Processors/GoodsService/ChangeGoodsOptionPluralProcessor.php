<?php

namespace App\Processors\GoodsService;

use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\OptionOneModel;
use App\Processors\AbstractCore;

class ChangeGoodsOptionPluralProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
//        $goodsId = $this->message->getField('data.goods_id');
//        $optionId = $this->message->getField('data.option_id');
//        $optionValueId = $this->message->getField('data.value_id');
//        $optionValue = (array)$this->message->getField('data.value');
//
//        $elasticGoodsModel = new GoodsModel();
//        $optionOneModel = new OptionOneModel();
//        $optionOne = $optionOneModel->setSelectionSet(['name', 'state', 'type'])->getById($optionId);
//
//        if ($optionValueId === 0) {
//            $values = [
//                [
//                    'id' => $optionValueId,
//                    'status' => 0
//                ]
//            ];
//        }
//        $goodsData = [
//            'id' => $goodsId,
//            'options' => [
//                [
//                    'details' => [
//                        'id' => $optionId,
//                        'name' => $optionOne['name'],
//                        'type' => $optionOne['type'],
//                        'state' => $optionOne['state'],
//                    ],
//                    'values' => [
//                        [
//                            'id' => $optionValueId,
//                            'status' => '',
//                            'name' => $optionValue['name'],
//                        ]
//                    ]
//                ]
//            ]
//        ];
//
//        dump($optionOne);die;
//
//        dump($this->message->getBody());die;
        // TODO: Implement doJob() method.
    }
}
