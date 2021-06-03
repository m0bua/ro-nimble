<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasWriteDb;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\GoodsOption
 *
 * @property int $id
 * @property int|null $goods_id
 * @property int|null $option_id
 * @property string|null $type
 * @property string|null $value
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $needs_index
 * @property-read Goods|null $goods
 * @property-read Option|null $option
 * @method static Builder|GoodsOption newModelQuery()
 * @method static Builder|GoodsOption newQuery()
 * @method static Builder|GoodsOption query()
 * @method static Builder|GoodsOption whereCreatedAt($value)
 * @method static Builder|GoodsOption whereGoodsId($value)
 * @method static Builder|GoodsOption whereId($value)
 * @method static Builder|GoodsOption whereIsDeleted($value)
 * @method static Builder|GoodsOption whereNeedsIndex($value)
 * @method static Builder|GoodsOption whereOptionId($value)
 * @method static Builder|GoodsOption whereType($value)
 * @method static Builder|GoodsOption whereUpdatedAt($value)
 * @method static Builder|GoodsOption whereValue($value)
 * @mixin Eloquent
 */
class GoodsOption extends Model
{
    use HasFactory;
    use HasFillable;
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $fillable = [
        'id',
        'goods_id',
        'option_id',
        'type',
        'value',
        'is_deleted',
        'needs_index',
    ];

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}