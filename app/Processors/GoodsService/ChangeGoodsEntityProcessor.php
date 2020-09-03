<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Models\GraphQL\ProducerOneModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;

class ChangeGoodsEntityProcessor extends AbstractCore
{
    /**
     * @inheritDoc
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $goodsId = $this->message->getField('data.id');
        $goodsData = (array)$this->message->getField('data');

        $oldAttributes = $elasticGoodsModel->searchById($goodsId);

        if (empty($oldAttributes)) {
            $goodsData['producer'] = (new ProducerOneModel())
                ->setSelectionSet(['producer_id:id', 'producer_title:title', 'producer_name:name'])
                ->setArgumentsWhere(
                    'id_eq',
                    $this->message->getField('data.producer_id')
                )
                ->get();
        }

        $elasticGoodsModel->load($oldAttributes);

        $commonFormatter = new CommonFormatter($goodsData);
        $commonFormatter->formatGoodsForIndex();
        $elasticGoodsModel->load($commonFormatter->getFormattedData());

        return Processor::CODE_SUCCESS;
    }
}
