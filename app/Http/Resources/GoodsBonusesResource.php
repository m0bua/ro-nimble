<?php

namespace App\Http\Resources;

class GoodsBonusesResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'bonus_charge_pcs',
            'comment_photo_bonus_charge',
            'bonus_not_allowed_pcs',
            'comment_video_child_bonus_charge',
            'comment_bonus_charge',
            'premium_bonus_charge_pcs',
            'use_instant_bonus',
            'comment_video_bonus_charge',
        ];
    }
}
