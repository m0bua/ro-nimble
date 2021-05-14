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
 * @property string|null $title
 * @property string|null $status
 * @property string|null $status_inherited
 * @property int|null $order
 * @property int|null $ext_id
 * @property string|null $name
 * @property string|null $titles_mode
 * @property string|null $kits_show
 * @property int|null $parent_id
 * @property int|null $left_key
 * @property int|null $right_key
 * @property int|null $level
 * @property string|null $sections_list
 * @property string|null $href
 * @property string|null $rz_mpath
 * @property bool|null $allow_index_three_parameters
 * @property string|null $on_subdomain
 * @property string|null $oversized
 * @property bool|null $is_subdomain
 * @property bool|null $disable_kit_ratio
 * @property bool|null $is_rozetka_top
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereAllowIndexThreeParameters($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDisableKitRatio($value)
 * @method static Builder|Category whereExtId($value)
 * @method static Builder|Category whereHref($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereIsDeleted($value)
 * @method static Builder|Category whereIsRozetkaTop($value)
 * @method static Builder|Category whereIsSubdomain($value)
 * @method static Builder|Category whereKitsShow($value)
 * @method static Builder|Category whereLeftKey($value)
 * @method static Builder|Category whereLevel($value)
 * @method static Builder|Category whereMpath($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereOnSubdomain($value)
 * @method static Builder|Category whereOrder($value)
 * @method static Builder|Category whereOversized($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereRightKey($value)
 * @method static Builder|Category whereRzMpath($value)
 * @method static Builder|Category whereSectionsList($value)
 * @method static Builder|Category whereStatus($value)
 * @method static Builder|Category whereStatusInherited($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereTitlesMode($value)
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
        'title',
        'status',
        'status_inherited',
        'order',
        'ext_id',
        'name',
        'titles_mode',
        'kits_show',
        'parent_id',
        'left_key',
        'right_key',
        'level',
        'sections_list',
        'href',
        'rz_mpath',
        'allow_index_three_parameters',
        'on_subdomain',
        'oversized',
        'is_subdomain',
        'disable_kit_ratio',
        'is_rozetka_top',
        'is_deleted',
    ];
}
