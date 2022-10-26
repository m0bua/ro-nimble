<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasDynamicBinds;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\OptionValueFactory;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;

use App\Models\Eloquent\AbstractModel as Model;

/**
 * App\Models\Eloquent\OptionValue
 *
 * @property int $id
 * @property int|null $option_id
 * @property int|null $parent_id
 * @property string|null $ext_id
 * @property string $title
 * @property string|null $name
 * @property string|null $status
 * @property int|null $order
 * @property string|null $similars_value
 * @property int|null $show_value_in_short_set
 * @property string|null $color
 * @property string $description
 * @property string $shortening
 * @property int|null $record_type
 * @property int|null $is_section
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $need_delete
 * @property-read Option|null $option
 * @property-read OptionValue|null $parent
 * @property-read Collection|OptionValueTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static OptionValueFactory factory(...$parameters)
 * @method static Builder|OptionValue loadTranslations() WARNING! This scope must be in start of all query
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
    use HasTranslations;
    use HasDynamicBinds;

    public $incrementing = false;

    public const STATUS_NOT_USE = 'not-use';
    public const STATUS_ACTIVE = 'active';

    protected $fillable = [
        'id',
        'option_id',
        'parent_id',
        'ext_id',
        'name',
        'status',
        'order',
        'similars_value',
        'show_value_in_short_set',
        'color',
        'record_type',
        'is_deleted',
        'is_section',
        'title',
        'description',
        'shortening',
    ];

    protected $casts = [
        'title' => Translatable::class,
        'description' => Translatable::class,
        'shortening' => Translatable::class,
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withDefault();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class)->withDefault();
    }

    /**
     * @param int $optionId
     * @param array $names
     * @param array $allOptionValueNames
     * @return array
     */
    public static function getOptValueIdsByNames(int $optionId, array $names, array $allOptionValueNames): array
    {
        return static::select([DB::raw("COALESCE(ov_parent.id, ov.id) as id")])
            ->from('option_values', 'ov')
            ->leftJoin('option_values as ov_parent', function (JoinClause $join) {
                $join->on('ov.parent_id', '=', 'ov_parent.id')
                    ->whereColumn('ov_parent.status', '!=', DB::raw("'not-use'"));
            })
            ->leftJoin('option_value_relations as ovr', 'ov.option_id',  'ovr.option_id')
            ->leftJoin('option_values as ov2r', 'ov2r.id',  'ovr.value_id')
            ->where('ov.option_id', $optionId)
            ->whereIn('ov.name', $names)
            ->whereNested(function($query) use ($allOptionValueNames) {
                $query->whereNull('ovr.id')
                    ->orWhereIn('ov2r.name', $allOptionValueNames);
            })
            ->pluck('id')
            ->toArray();
    }
}
