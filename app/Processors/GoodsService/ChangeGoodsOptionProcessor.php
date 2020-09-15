<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\OptionOneModel;
use App\Processors\AbstractCore;
use App\Helpers\ArrayHelper;
use App\ValueObjects\Processor;

class ChangeGoodsOptionProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $goodsId = $this->message->getField('data.goods_id');
        $optionId = $this->message->getField('data.option_id');
        $optionValue = $this->message->getField('data.value');

        $elasticGoodsModel = new GoodsModel();
        $optionOneModel = new OptionOneModel();
        $optionOne = $optionOneModel->setSelectionSet(['name', 'state', 'type'])->getById($optionId);

        $goodsData = [
            'id' => $goodsId,
            'options' => [
                [
                    'details' => [
                        'id' => $optionId,
                        'name' => $optionOne['name'],
                        'type' => $optionOne['type'],
                        'state' => $optionOne['state'],
                    ],
                    'value' => $optionValue
                ]
            ]
        ];

        $formatter = new CommonFormatter($goodsData);
        $formatter->formatGoodsForIndex();
        $formatter->formatOptionsForIndex();
        $formattedData = $formatter->getFormattedData();
        $currentData = $elasticGoodsModel->one($elasticGoodsModel->searchById($goodsId));
        $newData = ArrayHelper::merge($currentData, $formattedData);
        $elasticGoodsModel->load($newData)->index();

        return Processor::CODE_SUCCESS;
    }
}
