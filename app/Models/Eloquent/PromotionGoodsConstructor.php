<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\PromotionGoodsConstructorFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\PromotionGoodsConstructor
 *
 * @property int $id
 * @property int $constructor_id
 * @property int $goods_id
 * @property int $needs_index
 * @property int $is_deleted
 * @property int $order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_migrate
 * @property int $need_delete
 * @property-read PromotionConstructor $constructor
 * @property-read Goods $goods
 * @method static PromotionGoodsConstructorFactory factory(...$parameters)
 * @method static Builder|PromotionGoodsConstructor markedAsDeleted()
 * @method static Builder|PromotionGoodsConstructor needsIndex()
 * @method static Builder|PromotionGoodsConstructor needsMigrate()
 * @method static Builder|PromotionGoodsConstructor newModelQuery()
 * @method static Builder|PromotionGoodsConstructor newQuery()
 * @method static Builder|PromotionGoodsConstructor query()
 * @method static Builder|PromotionGoodsConstructor whereConstructorId($value)
 * @method static Builder|PromotionGoodsConstructor whereCreatedAt($value)
 * @method static Builder|PromotionGoodsConstructor whereGoodsId($value)
 * @method static Builder|PromotionGoodsConstructor whereId($value)
 * @method static Builder|PromotionGoodsConstructor whereIsDeleted($value)
 * @method static Builder|PromotionGoodsConstructor whereNeedsIndex($value)
 * @method static Builder|PromotionGoodsConstructor whereNeedsMigrate($value)
 * @method static Builder|PromotionGoodsConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionGoodsConstructor extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'id',
        'constructor_id',
        'goods_id',
        'needs_index',
        'is_deleted',
        'needs_migrate',
        'order',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class)->withDefault();
    }

    public function constructor(): BelongsTo
    {
        return $this->belongsTo(PromotionConstructor::class, 'constructor_id')->withDefault();
    }

    public function scopeMarkedAsDeleted(Builder $builder): Builder
    {
        return $builder->where('is_deleted', 1);
    }

    public function scopeNeedsIndex(Builder $builder): Builder
    {
        return $builder->where('needs_index', 1);
    }

    public function scopeNeedsMigrate(Builder $builder): Builder
    {
        return $builder->where('needs_migrate', 1);
    }
}
