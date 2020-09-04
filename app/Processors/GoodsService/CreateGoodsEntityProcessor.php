<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\ProducerOneModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;

class CreateGoodsEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $goodsData = (array)$this->message->getField('data');
        $goodsData['producer'] = (new ProducerOneModel())
            ->setSelectionSet(['producer_id:id', 'producer_title:title', 'producer_name:name'])
            ->setArgumentsWhere(
                'id_eq',
                $this->message->getField('data.producer_id')
            )
            ->get();

        $commonFormatter = new CommonFormatter($goodsData);
        $commonFormatter->formatGoodsForIndex();
        $formattedData = $commonFormatter->getFormattedData();

        $elasticGoodsModel->load($formattedData);

        return Processor::CODE_SUCCESS;
    }
}
