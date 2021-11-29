<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Traits\Eloquent\HasFillable;
use App\Traits\Eloquent\HasTranslations;
use App\ValueObjects\Options;
use Database\Factories\Eloquent\OptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Eloquent\Option
 *
 * @property int $id
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
 * @property-read Collection|GoodsOption[] $goodsOptions
 * @property-read int|null $goods_options_count
 * @property-read Collection|OptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @property array<string> $title title translations
 * @method static OptionFactory factory(...$parameters)
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
 * @method static Builder|Option whereType($value)
 * @method static Builder|Option whereUpdatedAt($value)
 * @method static Builder|Option whereValueSeparator($value)
 * @mixin Eloquent
 */
class Option extends Model
{
    use HasFactory;
    use HasFillable;
    use HasTranslations;

    public $incrementing = false;

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

    protected $casts = [
        'title' => Translatable::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function goodsOptions(): HasMany
    {
        return $this->hasMany(GoodsOption::class);
    }

    public function getSpecificOptions(): array
    {
        $optionTable = self::getTable();
        $optionSettingsTable = OptionSetting::getModel()->getTable();

        return $this->query()->from($optionTable, 'o')
            ->selectRaw('DISTINCT(o.id) AS id')
            ->join("{$optionSettingsTable} as os", 'os.option_id', 'o.id')
            ->whereIn('os.comparable', ['main', 'bottom'])
            ->where('o.category_id', 0)
            ->whereNotIn('o.type', Options::OPTIONS_BY_TYPES['text'])
            ->pluck('id')->toArray();
    }
}
