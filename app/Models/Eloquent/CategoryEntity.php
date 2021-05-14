<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\CategoryEntity
 *
 * @property int $id
 * @property string $mpath
 * @property string $title
 * @property string $status
 * @property string $status_inherited
 * @property int $order
 * @property int $ext_id
 * @property string $name
 * @property string $titles_mode
 * @property string $kits_show
 * @property int $parent_id
 * @property int $left_key
 * @property int $right_key
 * @property int $level
 * @property bool $is_deleted
 * @property string $sections_list
 * @property string $href
 * @property string $rz_mpath
 * @property bool $allow_index_three_parameters
 * @property string $on_subdomain
 * @property string $oversized
 * @property bool $is_subdomain
 * @property bool $disable_kit_ratio
 * @property bool $is_rozetka_top
 * @property string $use_group_links
 * @property bool $show_comparison
 * @property bool $print_return_form
 * @property bool $returnless_goods
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CategoryEntity newModelQuery()
 * @method static Builder|CategoryEntity newQuery()
 * @method static Builder|CategoryEntity query()
 * @method static Builder|CategoryEntity whereAllowIndexThreeParameters($value)
 * @method static Builder|CategoryEntity whereCreatedAt($value)
 * @method static Builder|CategoryEntity whereDeletedAt($value)
 * @method static Builder|CategoryEntity whereDisableKitRatio($value)
 * @method static Builder|CategoryEntity whereExtId($value)
 * @method static Builder|CategoryEntity whereHref($value)
 * @method static Builder|CategoryEntity whereId($value)
 * @method static Builder|CategoryEntity whereIsDeleted($value)
 * @method static Builder|CategoryEntity whereIsRozetkaTop($value)
 * @method static Builder|CategoryEntity whereIsSubdomain($value)
 * @method static Builder|CategoryEntity whereKitsShow($value)
 * @method static Builder|CategoryEntity whereLeftKey($value)
 * @method static Builder|CategoryEntity whereLevel($value)
 * @method static Builder|CategoryEntity whereMpath($value)
 * @method static Builder|CategoryEntity whereName($value)
 * @method static Builder|CategoryEntity whereOnSubdomain($value)
 * @method static Builder|CategoryEntity whereOrder($value)
 * @method static Builder|CategoryEntity whereOversized($value)
 * @method static Builder|CategoryEntity whereParentId($value)
 * @method static Builder|CategoryEntity wherePrintReturnForm($value)
 * @method static Builder|CategoryEntity whereReturnlessGoods($value)
 * @method static Builder|CategoryEntity whereRightKey($value)
 * @method static Builder|CategoryEntity whereRzMpath($value)
 * @method static Builder|CategoryEntity whereSectionsList($value)
 * @method static Builder|CategoryEntity whereShowComparison($value)
 * @method static Builder|CategoryEntity whereStatus($value)
 * @method static Builder|CategoryEntity whereStatusInherited($value)
 * @method static Builder|CategoryEntity whereTitle($value)
 * @method static Builder|CategoryEntity whereTitlesMode($value)
 * @method static Builder|CategoryEntity whereUpdatedAt($value)
 * @method static Builder|CategoryEntity whereUseGroupLinks($value)
 * @mixin Eloquent
 */
class CategoryEntity extends Model
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
        'is_deleted',
        'sections_list',
        'href',
        'allow_index_three_parameters',
        'on_subdomain',
        'oversized',
        'is_subdomain',
        'disable_kit_ratio',
        'use_group_links',
        'show_comparison',
        'print_return_form',
        'returnless_goods',
        'deleted_at',
    ];
}
