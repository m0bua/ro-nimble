<?php

namespace App\Processors\MarketingService\Labels\LabelGoodsRelation;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Processors\Processor;
use App\Services\Buffers\RedisGoodsBufferService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UpsertEventProcessor extends Processor
{
    protected string $dataRoot = 'fields_data';

    private RedisGoodsBufferService $goodsBuffer;

    public function __construct(RedisGoodsBufferService $goodsBuffer)
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

        DB::table('goods_label')->insertOrIgnore($this->data);

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
