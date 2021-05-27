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
 * App\Models\Eloquent\OptionSetting
 *
 * @property int $id
 * @property int|null $category_id
 * @property string|null $option_id
 * @property int|null $order
 * @property int|null $print_order
 * @property string|null $status
 * @property bool|null $in_short_description
 * @property bool|null $is_comparable
 * @property bool|null $show_selected_filter_title
 * @property bool|null $option_to_print
 * @property bool|null $is_searchable
 * @property string|null $unit
 * @property string|null $comment
 * @property string|null $template
 * @property string|null $comparable
 * @property float|null $weight
 * @property bool|null $strict_equal_similars
 * @property bool|null $hide_block_in_filter
 * @property string|null $special_combobox_view
 * @property string|null $more_word
 * @property bool|null $disallow_import_filters_orders
 * @property string|null $number_template
 * @property bool|null $get_from_standard
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category|null $category
 * @property-read Option|null $option
 * @method static Builder|OptionSetting newModelQuery()
 * @method static Builder|OptionSetting newQuery()
 * @method static Builder|OptionSetting query()
 * @method static Builder|OptionSetting whereCategoryId($value)
 * @method static Builder|OptionSetting whereComment($value)
 * @method static Builder|OptionSetting whereComparable($value)
 * @method static Builder|OptionSetting whereCreatedAt($value)
 * @method static Builder|OptionSetting whereDisallowImportFiltersOrders($value)
 * @method static Builder|OptionSetting whereGetFromStandard($value)
 * @method static Builder|OptionSetting whereHideBlockInFilter($value)
 * @method static Builder|OptionSetting whereId($value)
 * @method static Builder|OptionSetting whereInShortDescription($value)
 * @method static Builder|OptionSetting whereIsComparable($value)
 * @method static Builder|OptionSetting whereIsSearchable($value)
 * @method static Builder|OptionSetting whereMoreWord($value)
 * @method static Builder|OptionSetting whereNumberTemplate($value)
 * @method static Builder|OptionSetting whereOptionId($value)
 * @method static Builder|OptionSetting whereOptionToPrint($value)
 * @method static Builder|OptionSetting whereOrder($value)
 * @method static Builder|OptionSetting wherePrintOrder($value)
 * @method static Builder|OptionSetting whereShowSelectedFilterTitle($value)
 * @method static Builder|OptionSetting whereSpecialComboboxView($value)
 * @method static Builder|OptionSetting whereStatus($value)
 * @method static Builder|OptionSetting whereStrictEqualSimilars($value)
 * @method static Builder|OptionSetting whereTemplate($value)
 * @method static Builder|OptionSetting whereUnit($value)
 * @method static Builder|OptionSetting whereUpdatedAt($value)
 * @method static Builder|OptionSetting whereWeight($value)
 * @mixin Eloquent
 */
class OptionSetting extends Model
{
    use HasFactory;
    use HasFillable;
    use HasWriteDb;

    protected $connection = 'nimble_read';

    protected $casts = [
        'in_short_description' => 'bool',
    ];

    protected $fillable = [
        'id',
        'category_id',
        'option_id',
        'order',
        'print_order',
        'status',
        'in_short_description',
        'is_comparable',
        'show_selected_filter_title',
        'option_to_print',
        'is_searchable',
        'unit',
        'comment',
        'template',
        'comparable',
        'weight',
        'strict_equal_similars',
        'hide_block_in_filter',
        'special_combobox_view',
        'more_word',
        'disallow_import_filters_orders',
        'number_template',
        'get_from_standard',
    ];

    public function getBoolAttributes(): array
    {
        return [
            'in_short_description',
            'is_comparable',
            'show_selected_filter_title',
            'option_to_print',
            'is_searchable',
            'strict_equal_similars',
            'hide_block_in_filter',
            'disallow_import_filters_orders',
            'get_from_standard',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
