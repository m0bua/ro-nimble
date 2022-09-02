<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Database\Factories\Eloquent\GoodsOptionPluralFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\GoodsOptionPlural
 *
 * @property int $id
 * @property int|null $goods_id
 * @property int|null $option_id
 * @property int|null $value_id
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_index
 * @property int $need_delete
 * @property-read Goods|null $goods
 * @property-read Option|null $option
 * @property-read OptionValue|null $value
 * @method static GoodsOptionPluralFactory factory(...$parameters)
 * @method static Builder|GoodsOptionPlural needsIndex()
 * @method static Builder|GoodsOptionPlural newModelQuery()
 * @method static Builder|GoodsOptionPlural newQuery()
 * @method static Builder|GoodsOptionPlural query()
 * @method static Builder|GoodsOptionPlural whereCreatedAt($value)
 * @method static Builder|GoodsOptionPlural whereGoodsId($value)
 * @method static Builder|GoodsOptionPlural whereId($value)
 * @method static Builder|GoodsOptionPlural whereIsDeleted($value)
 * @method static Builder|GoodsOptionPlural whereNeedsIndex($value)
 * @method static Builder|GoodsOptionPlural whereOptionId($value)
 * @method static Builder|GoodsOptionPlural whereUpdatedAt($value)
 * @method static Builder|GoodsOptionPlural whereValueId($value)
 * @mixin Eloquent
 */
class GoodsOptionPlural extends Model
{
    use HasFactory;
    use HasFillable;

    protected $table = 'goods_options_plural';

    protected $fillable = [
        'id',
        'goods_id',
        'option_id',
        'value_id',
        'is_deleted',
        'needs_index',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class)->withDefault();
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withDefault();
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(OptionValue::class)->withDefault();
    }

    public function scopeNeedsIndex(Builder $builder): Builder
    {
        return $builder->where('needs_index', 1);
    }
}
