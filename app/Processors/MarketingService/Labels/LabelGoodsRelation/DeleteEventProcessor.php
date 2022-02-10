<?php

namespace App\Processors\MarketingService\Labels\LabelGoodsRelation;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Interfaces\GoodsBuffer;
use App\Processors\Processor;
use Illuminate\Support\Facades\DB;

class DeleteEventProcessor extends Processor
{
    protected string $dataRoot = 'fields_data';

    private GoodsBuffer $goodsBuffer;

    /**
     * @param GoodsBuffer $goodsBuffer
     */
    public function __construct(GoodsBuffer $goodsBuffer)
    {
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @inheritDoc
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->beforeProcess();
        $this->setDataFromMessage($message);

        DB::table('goods_label')
            ->where([
                'label_id' => $this->data['label_id'],
                'goods_id' => $this->data['goods_id']
            ])
            ->delete();

        $this->afterProcess();

        return Codes::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    protected function afterProcess(): void
    {
        $this->goodsBuffer->add($this->data['goods_id']);
    }
}
