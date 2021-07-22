<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\OptionValue
 *
 * @property int $id
 * @property int|null $option_id
 * @property int|null $parent_id
 * @property string|null $ext_id
 * @property string|null $title
 * @property string|null $name
 * @property string|null $status
 * @property int|null $order
 * @property string|null $similars_value
 * @property int|null $show_value_in_short_set
 * @property string|null $color
 * @property string|null $title_genetive
 * @property string|null $title_accusative
 * @property string|null $title_prepositional
 * @property string|null $description
 * @property string|null $shortening
 * @property int|null $record_type
 * @property int|null $is_section
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Option|null $option
 * @property-read OptionValue|null $parent
 * @method static Builder|OptionValue newModelQuery()
 * @method static Builder|OptionValue newQuery()
 * @method static Builder|OptionValue query()
 * @method static Builder|OptionValue whereColor($value)
 * @method static Builder|OptionValue whereCreatedAt($value)
 * @method static Builder|OptionValue whereDescription($value)
 * @method static Builder|OptionValue whereExtId($value)
 * @method static Builder|OptionValue whereId($value)
 * @method static Builder|OptionValue whereIsDeleted($value)
 * @method static Builder|OptionValue whereIsSection($value)
 * @method static Builder|OptionValue whereName($value)
 * @method static Builder|OptionValue whereOptionId($value)
 * @method static Builder|OptionValue whereOrder($value)
 * @method static Builder|OptionValue whereParentId($value)
 * @method static Builder|OptionValue whereRecordType($value)
 * @method static Builder|OptionValue whereShortening($value)
 * @method static Builder|OptionValue whereShowValueInShortSet($value)
 * @method static Builder|OptionValue whereSimilarsValue($value)
 * @method static Builder|OptionValue whereStatus($value)
 * @method static Builder|OptionValue whereTitle($value)
 * @method static Builder|OptionValue whereTitleAccusative($value)
 * @method static Builder|OptionValue whereTitleGenetive($value)
 * @method static Builder|OptionValue whereTitlePrepositional($value)
 * @method static Builder|OptionValue whereUpdatedAt($value)
 * @mixin Eloquent
 */
class OptionValue extends Model
{
    use HasFactory;
    use HasFillable;


    protected $fillable = [
        'id',
        'option_id',
        'parent_id',
        'ext_id',
        'title',
        'name',
        'status',
        'order',
        'show_value_in_short_set',
        'color',
        'shortening',
        'record_type',
        'is_deleted',
        'is_section',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }
}
