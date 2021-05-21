<?php

namespace App\Processors\MarketingService;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\PromotionGroupConstructor;

class ChangePromotionConstructorGroupProcessor implements ProcessorInterface
{
    /**
     * Eloquent model for updating data
     *
     * @var PromotionGroupConstructor
     */
    protected PromotionGroupConstructor $model;

    /**
     * ChangePromotionConstructorGroupProcessor constructor.
     * @param PromotionGroupConstructor $model
     */
    public function __construct(PromotionGroupConstructor $model)
    {
        $this->model = $model;
    }

    public function processMessage(MessageInterface $message): int
    {
        $constructorId = $message->getField('fields_data.promotion_constructor_id');
        $groupId = $message->getField('fields_data.group_id');

        $this->model
            ->write()
            ->updateOrCreate(
                [
                    'constructor_id' => $constructorId,
                    'group_id' => $groupId,
                ],
                [
                    'needs_index' => 1,
                    'needs_migrate' => 1,
                ]);

        return Codes::SUCCESS;
    }
}
