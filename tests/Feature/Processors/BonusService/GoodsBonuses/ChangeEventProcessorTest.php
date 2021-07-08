<?php

namespace Tests\Feature\Processors\BonusService\GoodsBonuses;

use App\Models\Eloquent\Bonus;
use App\Processors\BonusService\GoodsBonuses\ChangeEventProcessor;
use Tests\Feature\Processors\ChangeProcessorTestCase;

class ChangeEventProcessorTest extends ChangeProcessorTestCase
{
    public static string $processorNamespace = ChangeEventProcessor::class;

    public static string $modelNamespace = Bonus::class;

    public static bool $hasOwnId = false;

    public static array $aliases = [
        'pl_comment_bonus_charge' => 'comment_bonus_charge',
        'pl_comment_photo_bonus_charge' => 'comment_photo_bonus_charge',
        'pl_comment_video_bonus_charge' => 'comment_video_bonus_charge',
        'pl_bonus_not_allowed_pcs' => 'bonus_not_allowed_pcs',
        'pl_comment_video_child_bonus_charge' => 'comment_video_child_bonus_charge',
        'pl_bonus_charge_pcs' => 'bonus_charge_pcs',
        'pl_use_instant_bonus' => 'use_instant_bonus',
        'pl_premium_bonus_charge_pcs' => 'premium_bonus_charge_pcs'
    ];

    public static ?array $uniqueColumns = [
        'goods_id',
    ];

    public static ?string $dataRoot = 'fields_data';
}
