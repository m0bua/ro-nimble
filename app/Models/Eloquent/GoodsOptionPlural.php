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
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $table = 'goods_options_plural';

    protected $fillable = [
        'id',
        'goods_id',
        'option_id',
        'value_id',
        'is_deleted',
        'needs_index',
    ];
}
