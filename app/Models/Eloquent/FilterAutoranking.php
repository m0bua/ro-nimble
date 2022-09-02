<?php

namespace App\Models\Eloquent;

use App\Traits\Eloquent\HasFillable;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\FilterAutoranking
 *
 * @property int $id
 * @property string $parent_id
 * @property string $filter_name
 * @property string $filter_value
 * @property int $filter_rank
 * @property bool $is_value_show
 * @property bool $is_filter_show
 * @property int $need_delete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|FilterAutoranking newModelQuery()
 * @method static Builder|FilterAutoranking newQuery()
 * @method static Builder|FilterAutoranking query()
 * @method static Builder|FilterAutoranking whereCreatedAt($value)
 * @method static Builder|FilterAutoranking whereFilterName($value)
 * @method static Builder|FilterAutoranking whereFilterRank($value)
 * @method static Builder|FilterAutoranking whereFilterValue($value)
 * @method static Builder|FilterAutoranking whereId($value)
 * @method static Builder|FilterAutoranking whereIsFilterShow($value)
 * @method static Builder|FilterAutoranking whereIsValueShow($value)
 * @method static Builder|FilterAutoranking whereParentId($value)
 * @method static Builder|FilterAutoranking whereUpdatedAt($value)
 * @mixin Eloquent
 */
class FilterAutoranking extends Model
{
    use HasFactory;
    use HasFillable;

    protected $table = 'filters_autoranking';

    protected $casts = [
        'is_value_show' => 'bool',
        'is_filter_show' => 'bool',
    ];

    protected $fillable = [
        'parent_id',
        'filter_name',
        'filter_value',
        'filter_rank',
        'is_value_show',
        'is_filter_show',
    ];

    /**
     * @param int $categoryId
     * @return Builder
     */
    public static function getOptionsQuery(int $categoryId): Builder
    {
        return static::query()
            ->select([
                'parent_id',
                'filter_name',
                'filter_rank',
                'is_filter_show'
            ])
            ->from(self::getModel()->getTable(), 'sfao')
            ->where('filter_name', '!=', 'producer')
            ->where('filter_name', '!=', 'price')
            ->where('filter_name', '!=', 'series')
            ->whereRaw("parent_id::int = {$categoryId}")
            ->groupBy(
                'filter_name',
                'parent_id',
                'filter_rank',
                'is_filter_show'
            );
    }

    /**
     * @param int $categoryId
     * @return Builder
     */
    public static function getOptionValuesQuery(int $categoryId): Builder
    {
        return static::query()
            ->select([
                'parent_id',
                'filter_name',
                'filter_value',
                'is_value_show'
            ])
            ->from(self::getModel()->getTable(), 'sfaov')
            ->where('filter_name', '!=', 'producer')
            ->where('filter_name', '!=', 'price')
            ->where('filter_name', '!=', 'series')
            ->whereRaw("parent_id::int = {$categoryId}");
    }
}
