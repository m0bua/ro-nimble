<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\SeriesTranslation
 *
 * @property int $id
 * @property int $series_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Series $entity
 * @method static Builder|SeriesTranslation newModelQuery()
 * @method static Builder|SeriesTranslation newQuery()
 * @method static Builder|SeriesTranslation query()
 * @method static Builder|SeriesTranslation whereColumn($value)
 * @method static Builder|SeriesTranslation whereCreatedAt($value)
 * @method static Builder|SeriesTranslation whereId($value)
 * @method static Builder|SeriesTranslation whereLang($value)
 * @method static Builder|SeriesTranslation whereSeriesId($value)
 * @method static Builder|SeriesTranslation whereUpdatedAt($value)
 * @method static Builder|SeriesTranslation whereValue($value)
 * @mixin Eloquent
 */
class SeriesTranslation extends Translation
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'lang',
        'column',
        'value',
    ];
}
