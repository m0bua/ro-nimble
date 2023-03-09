<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\SeriesRegional
 *
 * @property int $id
 * @property int $series_id
 * @property string $country
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Series $entity
 * @method static Builder|SeriesRegional newModelQuery()
 * @method static Builder|SeriesRegional newQuery()
 * @method static Builder|SeriesRegional query()
 * @method static Builder|SeriesRegional whereColumn($value)
 * @method static Builder|SeriesRegional whereCreatedAt($value)
 * @method static Builder|SeriesRegional whereId($value)
 * @method static Builder|SeriesRegional whereCountry($value)
 * @method static Builder|SeriesRegional whereSeriesId($value)
 * @method static Builder|SeriesRegional whereUpdatedAt($value)
 * @method static Builder|SeriesRegional whereValue($value)
 * @mixin Eloquent
 */
class SeriesRegional extends Regional
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'country',
        'column',
        'value',
    ];
}
