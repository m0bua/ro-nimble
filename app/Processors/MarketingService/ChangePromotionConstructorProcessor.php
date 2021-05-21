<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionConstructor;

class ChangePromotionConstructorProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionConstructor
     */
    protected PromotionConstructor $model;

    /**
     * ChangePromotionConstructorProcessor constructor.
     * @param PromotionConstructor $model
     */
    public function __construct(PromotionConstructor $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $id = $message->getField('fields_data.id');
        $promotionId = $message->getField('fields_data.promotion_id');
        $giftId = $message->getField('fields_data.gift_id');

        $this->model
            ->write()
            ->updateOrCreate(
                [
                    'id' => $id,
                ],
                [
                    'promotion_id' => $promotionId,
                    'gift_id' => $giftId,
                ]
            );

        return Codes::SUCCESS;
    }
}
