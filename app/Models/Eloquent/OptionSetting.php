<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\OptionSetting
 *
 * @property int $id
 * @property int $category_id
 * @property string $option_title
 * @property string $option_id
 * @property int $order
 * @property int $print_order
 * @property string $status
 * @property bool $in_short_description
 * @property bool $is_comparable
 * @property bool $show_selected_filter_title
 * @property bool $option_to_print
 * @property bool $is_searchable
 * @property string $unit
 * @property string $comment
 * @property string $template
 * @property string $comparable
 * @property float $weight
 * @property bool $strict_equal_similars
 * @property bool $hide_block_in_filter
 * @property string $more_word
 * @property string $title_genetive
 * @property string $title_accusative
 * @property string $title_prepositional
 * @property bool $disallow_import_filters_orders
 * @property string $number_template
 * @property bool $get_from_standard
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
 * @method static Builder|OptionSetting whereOptionTitle($value)
 * @method static Builder|OptionSetting whereOptionToPrint($value)
 * @method static Builder|OptionSetting whereOrder($value)
 * @method static Builder|OptionSetting wherePrintOrder($value)
 * @method static Builder|OptionSetting whereShowSelectedFilterTitle($value)
 * @method static Builder|OptionSetting whereStatus($value)
 * @method static Builder|OptionSetting whereStrictEqualSimilars($value)
 * @method static Builder|OptionSetting whereTemplate($value)
 * @method static Builder|OptionSetting whereTitleAccusative($value)
 * @method static Builder|OptionSetting whereTitleGenetive($value)
 * @method static Builder|OptionSetting whereTitlePrepositional($value)
 * @method static Builder|OptionSetting whereUnit($value)
 * @method static Builder|OptionSetting whereUpdatedAt($value)
 * @method static Builder|OptionSetting whereWeight($value)
 * @mixin Eloquent
 */
class OptionSetting extends Model
{
    use HasFactory;
    use HasFillable;

    protected $fillable = [
        'id',
        'category_id',
        'option_title',
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
        'more_word',
        'title_genetive',
        'title_accusative',
        'title_prepositional',
        'disallow_import_filters_orders',
        'number_template',
        'get_from_standard',
    ];
}
