<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionConstructor;

class DeletePromotionConstructorProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionConstructor
     */
    protected PromotionConstructor $model;

    /**
     * DeletePromotionConstructorProcessor constructor.
     * @param PromotionConstructor $model
     */
    public function __construct(PromotionConstructor $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $this->model
            ->write()
            ->where('id', $message->getField('fields_data.id'))
            ->update(['is_deleted' => 1]);

        return Codes::SUCCESS;
    }
}
