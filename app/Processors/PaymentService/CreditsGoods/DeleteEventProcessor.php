<?php

namespace App\Processors\PaymentService\CreditsGoods;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Goods;
use App\Processors\Processor;

class DeleteEventProcessor extends Processor
{
    /**
     * DeleteEventProcessor constructor.
     * @param Goods $model
     */
    public function __construct(Goods $model)
    {
        $this->model = $model;
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

        $goods->paymentMethods()->detach($paymentMethodIds);

        return Codes::SUCCESS;
    }
}
