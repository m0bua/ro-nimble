<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasWriteDb;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\PromotionConstructor
 *
 * @property int $id
 * @property int $promotion_id
 * @property int|null $gift_id
 * @property int $needs_index
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|PromotionConstructor newModelQuery()
 * @method static Builder|PromotionConstructor newQuery()
 * @method static Builder|PromotionConstructor query()
 * @method static Builder|PromotionConstructor whereCreatedAt($value)
 * @method static Builder|PromotionConstructor whereGiftId($value)
 * @method static Builder|PromotionConstructor whereId($value)
 * @method static Builder|PromotionConstructor whereIsDeleted($value)
 * @method static Builder|PromotionConstructor whereNeedsIndex($value)
 * @method static Builder|PromotionConstructor wherePromotionId($value)
 * @method static Builder|PromotionConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionConstructor extends Model
{
    use HasFactory;
    use HasFillable;
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $fillable = [
        'id',
        'promotion_id',
        'gift_id',
        'needs_index',
        'is_deleted',
    ];
}
