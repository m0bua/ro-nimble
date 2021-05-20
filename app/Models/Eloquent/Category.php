<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\Category
 *
 * @property int $id
 * @property int|null $mpath
 * @property int|null $order
 * @property string|null $name
 * @property int|null $parent_id
 * @property int|null $left_key
 * @property int|null $right_key
 * @property int|null $level
 * @property int $is_deleted
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereIsDeleted($value)
 * @method static Builder|Category whereLeftKey($value)
 * @method static Builder|Category whereLevel($value)
 * @method static Builder|Category whereMpath($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereOrder($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereRightKey($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'id',
        'mpath',
        'order',
        'name',
        'parent_id',
        'left_key',
        'right_key',
        'level',
        'is_deleted',
    ];
}
