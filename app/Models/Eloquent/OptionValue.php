<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasDynamicBinds;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use Database\Factories\Eloquent\OptionValueFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
 * @property string|null $name
 * @property string|null $status
 * @property int|null $order
 * @property string|null $similars_value
 * @property int|null $show_value_in_short_set
 * @property string|null $color
 * @property int|null $record_type
 * @property int|null $is_section
 * @property int $is_deleted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Option|null $option
 * @property-read OptionValue|null $parent
 * @property-read Collection|OptionValueTranslation[] $translations
 * @property-read int|null $translations_count
 * @property array<string> $title title translations
 * @property array<string> $title_genetive title_genetive translations
 * @property array<string> $title_accusative title_accusative translations
 * @property array<string> $title_prepositional title_prepositional translations
 * @property array<string> $description description translations
 * @property array<string> $shortening shortening translations
 * @method static OptionValueFactory factory(...$parameters)
 * @method static Builder|OptionValue newModelQuery()
 * @method static Builder|OptionValue newQuery()
 * @method static Builder|OptionValue query()
 * @method static Builder|OptionValue whereColor($value)
 * @method static Builder|OptionValue whereCreatedAt($value)
 * @method static Builder|OptionValue whereExtId($value)
 * @method static Builder|OptionValue whereId($value)
 * @method static Builder|OptionValue whereIsDeleted($value)
 * @method static Builder|OptionValue whereIsSection($value)
 * @method static Builder|OptionValue whereName($value)
 * @method static Builder|OptionValue whereOptionId($value)
 * @method static Builder|OptionValue whereOrder($value)
 * @method static Builder|OptionValue whereParentId($value)
 * @method static Builder|OptionValue whereRecordType($value)
 * @method static Builder|OptionValue whereShowValueInShortSet($value)
 * @method static Builder|OptionValue whereSimilarsValue($value)
 * @method static Builder|OptionValue whereStatus($value)
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
        'title_genetive',
        'title_accusative',
        'title_prepositional',
        'description',
        'shortening',
    ];

    protected $casts = [
        'title' => Translatable::class,
        'title_genetive' => Translatable::class,
        'title_accusative' => Translatable::class,
        'title_prepositional' => Translatable::class,
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
}
