<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\ProducerRegional
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
 * @method static Builder|ProducerRegional newModelQuery()
 * @method static Builder|ProducerRegional newQuery()
 * @method static Builder|ProducerRegional query()
 * @method static Builder|ProducerRegional whereColumn($value)
 * @method static Builder|ProducerRegional whereCreatedAt($value)
 * @method static Builder|ProducerRegional whereId($value)
 * @method static Builder|ProducerRegional whereCountry($value)
 * @method static Builder|ProducerRegional whereSeriesId($value)
 * @method static Builder|ProducerRegional whereUpdatedAt($value)
 * @method static Builder|ProducerRegional whereValue($value)
 * @mixin Eloquent
 */
class ProducerRegional extends Regional
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'country',
        'column',
        'value',
    ];
}
