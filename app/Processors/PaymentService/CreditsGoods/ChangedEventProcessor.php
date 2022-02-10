<?php

namespace App\Processors\PaymentService\CreditsGoods;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use App\Processors\Processor;
use App\Services\Buffers\RedisGoodsBufferService;

class ChangedEventProcessor extends Processor
{
    private RedisGoodsBufferService $goodsBuffer;

    /**
     * @param Goods $model
     * @param RedisGoodsBufferService $goodsBuffer
     */
    public function __construct(Goods $model, RedisGoodsBufferService $goodsBuffer)
    {
        $this->model = $model;
        $this->goodsBuffer = $goodsBuffer;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $goodsId = $message->getField('goods_id');
        $paymentMethodIds = $message->getField('credit_methods_for_goods');

        $goods = $this->model->findOrNew($goodsId);
        if (!$goods->exists) {
            $goods->id = $goodsId;
        }

        $goods->paymentMethods()->sync($paymentMethodIds);

        $this->goodsBuffer->add($goodsId);

        return Codes::SUCCESS;
    }
}
