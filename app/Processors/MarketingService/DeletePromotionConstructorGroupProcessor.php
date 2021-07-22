<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionGroupConstructor;

class DeletePromotionConstructorGroupProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionGroupConstructor
     */
    protected PromotionGroupConstructor $model;

    /**
     * DeletePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $this->model

            ->where('constructor_id', $message->getField('fields_data.promotion_constructor_id'))
            ->where('group_id', $message->getField('fields_data.group_id'))
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
