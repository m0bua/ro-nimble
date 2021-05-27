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
 * App\Models\Eloquent\Option
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $name
 * @property string|null $type
 * @property string|null $ext_id
 * @property int|null $parent_id
 * @property int|null $category_id
 * @property string|null $filtering_type
 * @property string|null $value_separator
 * @property string|null $state
 * @property string|null $for_record_type
 * @property int|null $order
 * @property int|null $record_type
 * @property string|null $option_record_comparable
 * @property string|null $option_record_status
 * @property bool|null $affect_group_photo
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Category|null $category
 * @method static Builder|Option newModelQuery()
 * @method static Builder|Option newQuery()
 * @method static Builder|Option query()
 * @method static Builder|Option whereAffectGroupPhoto($value)
 * @method static Builder|Option whereCategoryId($value)
 * @method static Builder|Option whereCreatedAt($value)
 * @method static Builder|Option whereExtId($value)
 * @method static Builder|Option whereFilteringType($value)
 * @method static Builder|Option whereForRecordType($value)
 * @method static Builder|Option whereId($value)
 * @method static Builder|Option whereIsDeleted($value)
 * @method static Builder|Option whereName($value)
 * @method static Builder|Option whereOptionRecordComparable($value)
 * @method static Builder|Option whereOptionRecordStatus($value)
 * @method static Builder|Option whereOrder($value)
 * @method static Builder|Option whereParentId($value)
 * @method static Builder|Option whereRecordType($value)
 * @method static Builder|Option whereState($value)
 * @method static Builder|Option whereTitle($value)
 * @method static Builder|Option whereType($value)
 * @method static Builder|Option whereUpdatedAt($value)
 * @method static Builder|Option whereValueSeparator($value)
 * @mixin Eloquent
 */
class Option extends Model
{
    use HasFactory;
    use HasFillable;
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $casts = [
        'affect_group_photo' => 'bool',
    ];

    protected $fillable = [
        'id',
        'title',
        'name',
        'type',
        'ext_id',
        'parent_id',
        'category_id',
        'filtering_type',
        'value_separator',
        'state',
        'for_record_type',
        'order',
        'record_type',
        'option_record_comparable',
        'option_record_status',
        'affect_group_photo',
        'is_deleted',
    ];

    public function getBoolAttributes(): array
    {
        return [
            'affect_group_photo',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
