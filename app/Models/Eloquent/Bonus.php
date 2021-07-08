<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\BonusFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Bonus
 *
 * @property int $id
 * @property int $goods_id
 * @property int $comment_bonus_charge
 * @property int $comment_photo_bonus_charge
 * @property int $comment_video_bonus_charge
 * @property bool $bonus_not_allowed_pcs
 * @property int $comment_video_child_bonus_charge
 * @property int $bonus_charge_pcs
 * @property bool $use_instant_bonus
 * @property int $premium_bonus_charge_pcs
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Goods $goods
 * @method static BonusFactory factory(...$parameters)
 * @method static Builder|Bonus newModelQuery()
 * @method static Builder|Bonus newQuery()
 * @method static Builder|Bonus query()
 * @method static Builder|Bonus whereBonusChargePcs($value)
 * @method static Builder|Bonus whereBonusNotAllowedPcs($value)
 * @method static Builder|Bonus whereCommentBonusCharge($value)
 * @method static Builder|Bonus whereCommentPhotoBonusCharge($value)
 * @method static Builder|Bonus whereCommentVideoBonusCharge($value)
 * @method static Builder|Bonus whereCommentVideoChildBonusCharge($value)
 * @method static Builder|Bonus whereCreatedAt($value)
 * @method static Builder|Bonus whereGoodsId($value)
 * @method static Builder|Bonus whereId($value)
 * @method static Builder|Bonus wherePremiumBonusChargePcs($value)
 * @method static Builder|Bonus whereUpdatedAt($value)
 * @method static Builder|Bonus whereUseInstantBonus($value)
 * @mixin Eloquent
 */
class Bonus extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'goods_id',
        'comment_bonus_charge',
        'comment_photo_bonus_charge',
        'comment_video_bonus_charge',
        'bonus_not_allowed_pcs',
        'comment_video_child_bonus_charge',
        'bonus_charge_pcs',
        'use_instant_bonus',
        'premium_bonus_charge_pcs',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class)->withDefault();
    }
}
