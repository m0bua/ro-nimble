<?php

namespace App\Models\Eloquent;

use App\Casts\Translatable;
use App\Enums\Filters;
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
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Eloquent\Option
 *
 * @property int $id
 * @property string $title
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
 * @property-read int|null $goods_options_count
 * @property-read Collection|OptionTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Option active()
 * @method static OptionFactory factory(...$parameters)
 * @method static Builder|Option loadTranslations() WARNING! This scope must be in start of all query
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
    use HasTranslations;

    public const TYPE_INTEGER = 'Integer';
    public const TYPE_DECIMAL = 'Decimal';
    public const TYPE_CHECKBOX = 'CheckBox';

    public const STATE_ACTIVE = 'active';

    public $incrementing = false;

    public static array $sliderTypes = [
        self::TYPE_INTEGER,
        self::TYPE_DECIMAL
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

    protected $casts = [
        'title' => Translatable::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
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

    /**
     * @param static|Builder $query
     */
    public function scopeActive($query)
    {
        return $query->where('state', 'active');
    }

    /**
     * @param array $names
     * @return \Illuminate\Support\Collection
     */
    public static function getOptsByNames(array $names): \Illuminate\Support\Collection
    {
        return static::whereIn('name', $names)->active()->get();
    }

    /**
     * @param Category $category
     * @return int
     */
    public static function getOptionAutorankingCount(Category $category): int
    {
        $optionSettingTable = OptionSetting::make()->getTable();
        $filterAutorankingOptionsQuery = FilterAutoranking::getOptionsQuery($category->id);

        return static::query()
            ->from(self::getModel()->getTable(), 'o')
            ->whereIn('o.category_id', Category::getParentsQuery($category))
            ->where('os.comparable', 'main')
            ->whereIn('os.status', ['facet', 'active'])
            ->join("{$optionSettingTable} as os", 'os.option_id', 'o.id')
            ->joinSub($filterAutorankingOptionsQuery, 'fao', 'fao.filter_name', 'o.name')
            ->count();
    }

    /**
     * @param array $optionsIds
     * @param array $categoryIds
     * @param bool $isFilterAutoranking
     * @param array $optionValues
     * @return SupportCollection
     */
    public function getOptionsByCategory(
        array $optionsIds,
        array $categoryIds,
        bool $isFilterAutoranking,
        array $optionValues
    ): SupportCollection
    {
        $optionTable = $this->getTable();
        $optionValueTable = OptionValue::make()->getTable();
        $optionSettingTable = OptionSetting::make()->getTable();
        $optionValueRelation = OptionValueRelation::make()->getTable();
        $precountOptionSettingTable = PrecountOptionSetting::make()->getTable();

        $query = static::query()
            ->select([
                'o.id as option_id',
                'o.name as option_name',
                'o.type',
                'ov.color as option_value_color',
                'ov.id as option_value_id',
                'ov.name as option_value_name',
                'os.id as option_setting_id',
                'os.order',
                'os.special_combobox_view as special_combobox_view',
                'os.comparable',
            ])
            ->from($optionTable, 'o')
            ->join("{$precountOptionSettingTable} as pos", 'pos.option_id', 'o.id')
            ->join("{$optionSettingTable} as os", 'os.id', 'pos.options_settings_id')
            ->leftJoin("{$optionValueRelation} as ovr", 'ovr.option_id', 'o.id')
            ->leftJoin("{$optionValueTable} as ov", function(JoinClause $join) {
                $join->on('ov.option_id', 'o.id')
                    ->where('ov.status', OptionValue::STATUS_ACTIVE);
            })
            ->whereIn('o.id', $optionsIds)
            ->whereIn('pos.category_id', $categoryIds)
            ->whereIn('os.comparable', [Filters::COMPARABLE_MAIN, Filters::COMPARABLE_BOTTOM])
            ->where('o.state', self::STATE_ACTIVE)
            ->whereIn('os.status', OptionSetting::$availableStatuses)
            ->whereNotIn('o.type', self::$sliderTypes)
            ->whereNested(function ($q) use ($optionValues) {
                $q->whereNull('ovr.id')
                    ->orWhereIn('ovr.value_id', $optionValues);
            });

        if ($isFilterAutoranking) {
            $filterAutorankingOptionsQuery = FilterAutoranking::getOptionsQuery($categoryIds[0]);
            $filterAutorankingOptionValuesQuery = FilterAutoranking::getOptionValuesQuery($categoryIds[0]);

            $query
                ->addSelect([
                    DB::raw('COALESCE(faov.is_value_show, 0) as is_value_show'),
                    DB::raw('(CASE
                        WHEN COALESCE(faov.is_value_show, 0) = 0 THEN 1 ELSE 0
                    END) as hide_block')
                ])
                ->joinSub($filterAutorankingOptionsQuery, 'fao', 'fao.filter_name', 'o.name')
                ->joinSub($filterAutorankingOptionValuesQuery, 'faov', function(JoinClause $join) {
                    $join->on('faov.filter_name', 'o.name')
                        ->whereRaw('(faov.filter_value = ov.name or faov.filter_value = ov.id::text)');
                })
                ->orderBy('fao.filter_rank')
                ->orderByRaw('(CASE
                        WHEN os.disallow_import_filters_orders = false
                        THEN COALESCE(faov.is_value_show, 0)
                        ELSE 1
                    END) desc')
                ->orderBy('ov.order');
        } else {
            $query
                ->addSelect([
                    'os.hide_block_in_filter as hide_block',
                    DB::raw('0 as is_value_show')
                ])
                ->orderBy('os.order')
                ->orderBy('ov.order');
        }

        return $query->get()->recursive();
    }

    /**
     * @param array $categoryIds
     * @return SupportCollection
     */
    public function getSliders(array $categoryIds): SupportCollection
    {
        $optionTable = $this->getTable();
        $precountOptionSliderTable = PrecountOptionSlider::make()->getTable();
        $optionSettingTable = OptionSetting::make()->getTable();

        $query = static::query()
            ->select([
                'o.id as option_id',
                'o.name as option_name',
                'o.type as option_type',
                'os.id as option_setting_id',
                'os.special_combobox_view',
                'os.order',
                'os.category_id as category_id',
                'pos.min_value',
                'pos.max_value'
            ])
            ->from($precountOptionSliderTable, 'pos')
            ->join("{$optionTable} as o", 'pos.option_id', 'o.id')
            ->join("{$optionSettingTable} as os", 'os.option_id', 'o.id')
            ->whereIn('pos.category_id', $categoryIds)
            ->where('o.state', self::STATE_ACTIVE)
            ->whereIn('o.type', self::$sliderTypes)
            ->whereNotIn('os.comparable', [Filters::COMPARABLE_DISABLE, Filters::COMPARABLE_LOCKED])
            ->where(function(Builder $query) use ($categoryIds) {
                $query->whereIn('os.category_id', $categoryIds)
                    ->orWhere('os.category_id', 0)
                    ->orWhereNull('os.category_id');
            })
            ->orderBy('option_id')
            ->orderBy('category_id');

        //если одинаковых опций пришло две, оставляем ту что с категорией
        return $query->get()->keyBy('option_id')->values()->recursive();
    }
}
