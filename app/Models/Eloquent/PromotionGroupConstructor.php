<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\PromotionGroupConstructorFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\PromotionGroupConstructor
 *
 * @property int $id
 * @property int $constructor_id
 * @property int $group_id
 * @property int $needs_index
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_migrate
 * @property int $need_delete
 * @property-read PromotionConstructor $constructor
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @method static PromotionGroupConstructorFactory factory(...$parameters)
 * @method static Builder|PromotionGroupConstructor markedAsDeleted()
 * @method static Builder|PromotionGroupConstructor needsIndex()
 * @method static Builder|PromotionGroupConstructor needsMigrate()
 * @method static Builder|PromotionGroupConstructor newModelQuery()
 * @method static Builder|PromotionGroupConstructor newQuery()
 * @method static Builder|PromotionGroupConstructor query()
 * @method static Builder|PromotionGroupConstructor whereConstructorId($value)
 * @method static Builder|PromotionGroupConstructor whereCreatedAt($value)
 * @method static Builder|PromotionGroupConstructor whereGroupId($value)
 * @method static Builder|PromotionGroupConstructor whereId($value)
 * @method static Builder|PromotionGroupConstructor whereIsDeleted($value)
 * @method static Builder|PromotionGroupConstructor whereNeedsIndex($value)
 * @method static Builder|PromotionGroupConstructor whereNeedsMigrate($value)
 * @method static Builder|PromotionGroupConstructor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PromotionGroupConstructor extends Model
{
    use HasFactory;
    use HasFillable;


    protected $table = 'promotion_groups_constructors';

    protected $fillable = [
        'id',
        'constructor_id',
        'group_id',
        'needs_migrate',
        'needs_index',
        'is_deleted',
    ];

    public function constructor(): BelongsTo
    {
        return $this->belongsTo(PromotionConstructor::class, 'constructor_id')->withDefault();
    }

    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'group_id', 'group_id');
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
