<?php

namespace App\Http\Resources;

class GoodsBonusesResource extends BaseResource
{
    public function getResourceFields(): array
    {
        return [
            'bonus_charge_pcs',
            'use_instant_bonus',
            'premium_bonus_charge_pcs',
        ];
    }
}
