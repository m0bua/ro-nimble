<?php

namespace App\Processors\GoodsService;

use App\Helpers\CommonFormatter;
use App\Models\Elastic\GoodsModel;
use App\Processors\AbstractCore;
use App\ValueObjects\Processor;
use Exception;

class ChangeGoodsEntityProcessor extends AbstractCore
{
    /**
     * @return int
     * @throws Exception
     */
    public function doJob()
    {
        $elasticGoodsModel = new GoodsModel();
        $goodsId = $this->message->getField('data.id');
        $goodsData = (array)$this->message->getField('data');

        $currentData = $elasticGoodsModel->one($elasticGoodsModel->searchById($goodsId));

        $elasticGoodsModel->load($currentData);
        $commonFormatter = new CommonFormatter($goodsData);
        $commonFormatter->formatGoodsForIndex();
        $formattedData = $commonFormatter->getFormattedData();

        $elasticGoodsModel
            ->load($formattedData, ['producer_id' => 'setProducerIdWithExtra'])
            ->index();

        return Processor::CODE_SUCCESS;
    }
}
