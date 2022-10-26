<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\PaymentMethodFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\PaymentMethod
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string|null $name
 * @property int|null $order
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Collection|PaymentMethod[] $children
 * @property-read int|null $children_count
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @property-read PaymentMethod|null $parent
 * @property-read Collection|PaymentMethodTranslation[] $translations
 * @property-read int|null $translations_count
 * @property string $title title translation
 * @method static Builder|PaymentMethod active()
 * @method static PaymentMethodFactory factory(...$parameters)
 * @method static Builder|PaymentMethod loadTranslations() WARNING! This scope must be in start of all query
 * @method static Builder|PaymentMethod newModelQuery()
 * @method static Builder|PaymentMethod newQuery()
 * @method static Builder|PaymentMethod query()
 * @method static Builder|PaymentMethod whereCreatedAt($value)
 * @method static Builder|PaymentMethod whereId($value)
 * @method static Builder|PaymentMethod whereName($value)
 * @method static Builder|PaymentMethod whereOrder($value)
 * @method static Builder|PaymentMethod whereParentId($value)
 * @method static Builder|PaymentMethod whereStatus($value)
 * @method static Builder|PaymentMethod whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'parent_id',
        'payment_term_id',
        'name',
        'order',
        'status',
        'title'
    ];

    protected $casts = [
        'title' => Translatable::class
    ];

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id')->withDefault();
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function terms(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodsTerm::class, 'payment_term_id');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', '=', 'active');
    }
}
