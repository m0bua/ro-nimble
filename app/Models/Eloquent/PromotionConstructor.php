<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\PromotionConstructorFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\PromotionConstructor
 *
 * @property int $id
 * @property int $promotion_id
 * @property int|null $gift_id
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $need_delete
 * @property-read Collection|PromotionGoodsConstructor[] $goodsConstructors
 * @property-read int|null $goods_constructors_count
 * @property-read Collection|PromotionGroupConstructor[] $groupConstructors
 * @property-read int|null $group_constructors_count
 * @method static PromotionConstructorFactory factory(...$parameters)
 * @method static Builder|PromotionConstructor markedAsDeleted()
 * @method static Builder|PromotionConstructor newModelQuery()
 * @method static Builder|PromotionConstructor newQuery()
 * @method static Builder|PromotionConstructor query()
 * @method static Builder|PromotionConstructor whereCreatedAt($value)
 * @method static Builder|PromotionConstructor whereGiftId($value)
 * @method static Builder|PromotionConstructor whereId($value)
 * @method static Builder|PromotionConstructor whereIsDeleted($value)
 * @method static Builder|PromotionConstructor wherePromotionId($value)
 * @method static Builder|PromotionConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionConstructor extends Model
{
    use HasFactory;
    use HasFillable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'promotion_id',
        'gift_id',
        'is_deleted',
    ];

    public function scopeMarkedAsDeleted(Builder $builder): Builder
    {
        return $builder->where('is_deleted', 1);
    }

    public function groupConstructors(): HasMany
    {
        return $this->hasMany(PromotionGroupConstructor::class, 'constructor_id');
    }

    public function goodsConstructors(): HasMany
    {
        return $this->hasMany(PromotionGoodsConstructor::class, 'constructor_id');
    }
}
