<?php

namespace App\Processors\BonusService\GoodsBonuses;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Bonus;
use App\Processors\AbstractProcessor;

class CreateProcessor extends AbstractProcessor
{
    protected Bonus $model;

    protected static array $aliases = [
        'pl_comment_bonus_charge' => 'comment_bonus_charge',
        'pl_comment_photo_bonus_charge' => 'comment_photo_bonus_charge',
        'pl_comment_video_bonus_charge' => 'comment_video_bonus_charge',
        'pl_bonus_not_allowed_pcs' => 'bonus_not_allowed_pcs',
        'pl_comment_video_child_bonus_charge' => 'comment_video_child_bonus_charge',
        'pl_bonus_charge_pcs' => 'bonus_charge_pcs',
        'pl_use_instant_bonus' => 'use_instant_bonus',
        'pl_premium_bonus_charge_pcs' => 'premium_bonus_charge_pcs',
    ];

    /**
     * CreateProcessor constructor.
     * @param Bonus $model
     */
    public function __construct(Bonus $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     */
    public function processMessage(MessageInterface $message): int
    {
        $rawData = (array)$message->getField('fields_data');
        $data = $this->prepareData($rawData, self::$aliases);

        $this->model->create($data);

        return Codes::SUCCESS;
    }
}
