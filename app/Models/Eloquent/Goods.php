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
 * App\Models\Eloquent\Goods
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $category_id
 * @property string|null $mpath
 * @property float|null $price
 * @property float|null $rank
 * @property string|null $sell_status
 * @property int|null $producer_id
 * @property int|null $seller_id
 * @property int|null $group_id
 * @property int|null $is_group_primary
 * @property string|null $status_inherited
 * @property int|null $order
 * @property int|null $series_id
 * @property string|null $state
 * @property int $needs_index
 * @property int $is_deleted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Goods newModelQuery()
 * @method static Builder|Goods newQuery()
 * @method static Builder|Goods query()
 * @method static Builder|Goods whereCategoryId($value)
 * @method static Builder|Goods whereCreatedAt($value)
 * @method static Builder|Goods whereGroupId($value)
 * @method static Builder|Goods whereId($value)
 * @method static Builder|Goods whereIsDeleted($value)
 * @method static Builder|Goods whereIsGroupPrimary($value)
 * @method static Builder|Goods whereMpath($value)
 * @method static Builder|Goods whereName($value)
 * @method static Builder|Goods whereNeedsIndex($value)
 * @method static Builder|Goods whereOrder($value)
 * @method static Builder|Goods wherePrice($value)
 * @method static Builder|Goods whereProducerId($value)
 * @method static Builder|Goods whereRank($value)
 * @method static Builder|Goods whereSellStatus($value)
 * @method static Builder|Goods whereSellerId($value)
 * @method static Builder|Goods whereSeriesId($value)
 * @method static Builder|Goods whereState($value)
 * @method static Builder|Goods whereStatusInherited($value)
 * @method static Builder|Goods whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Goods extends Model
{
    use HasFactory;
    use HasFillable;
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $fillable = [
        'id',
        'name',
        'category_id',
        'mpath',
        'price',
        'rank',
        'sell_status',
        'producer_id',
        'seller_id',
        'group_id',
        'is_group_primary',
        'status_inherited',
        'order',
        'series_id',
        'state',
        'needs_index',
        'is_deleted',
    ];
}
