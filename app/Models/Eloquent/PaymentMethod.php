<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\PaymentMethodFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
 * @property-read Collection|PaymentMethod[] $children
 * @property-read int|null $children_count
 * @property-read Collection|Goods[] $goods
 * @property-read int|null $goods_count
 * @property-read PaymentMethod|null $parent
 * @method static PaymentMethodFactory factory(...$parameters)
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

    public $incrementing = false;

    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'order',
        'status',
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
}
