<?php

namespace App\Processors\BonusService\GoodsBonuses;

use App\Cores\ConsumerCore\Interfaces\MessageInterface;
use App\Cores\ConsumerCore\Interfaces\ProcessorInterface;
use App\Cores\Shared\Codes;
use App\Models\Eloquent\Bonus;
use Exception;

class ChangeProcessor implements ProcessorInterface
{
    protected Bonus $model;

    /**
     * ChangeProcessor constructor.
     * @param Bonus $model
     */
    public function __construct(Bonus $model)
    {
        $this->model = $model;
    }

    /**
     * @param MessageInterface $message
     * @return int
     * @throws Exception
     */
    public function processMessage(MessageInterface $message): int
    {
        $rawData = (array)$message->getField('fields_data');
        $goodsId = $rawData['goods_id'];
        if (!isset($goodsId)) {
            throw new Exception('goods_id is incorrect');
        }

        $data = [
            'comment_bonus_charge' => $rawData['pl_comment_bonus_charge'] ?? null,
            'comment_photo_bonus_charge' => $rawData['pl_comment_photo_bonus_charge'] ?? null,
            'comment_video_bonus_charge' => $rawData['pl_comment_video_bonus_charge'] ?? null,
            'bonus_not_allowed_pcs' => ($rawData['pl_bonus_not_allowed_pcs'] ?? null) ? 't' : 'f',
            'comment_video_child_bonus_charge' => $rawData['pl_comment_video_child_bonus_charge'] ?? null,
            'bonus_charge_pcs' => $rawData['pl_bonus_charge_pcs'] ?? null,
            'use_instant_bonus' => ($rawData['pl_use_instant_bonus'] ?? null) ? 't' : 'f',
            'premium_bonus_charge_pcs' => $rawData['pl_premium_bonus_charge_pcs'] ?? null,
        ];

        $this->model

            ->whereGoodsId($goodsId)
            ->update($data);

        return Codes::SUCCESS;
    }
}
