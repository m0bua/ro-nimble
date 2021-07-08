<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\ProducerTranslation
 *
 * @property int $id
 * @property int|null $producer_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Producer $entity
 * @method static Builder|ProducerTranslation newModelQuery()
 * @method static Builder|ProducerTranslation newQuery()
 * @method static Builder|ProducerTranslation query()
 * @method static Builder|ProducerTranslation whereColumn($value)
 * @method static Builder|ProducerTranslation whereCreatedAt($value)
 * @method static Builder|ProducerTranslation whereId($value)
 * @method static Builder|ProducerTranslation whereLang($value)
 * @method static Builder|ProducerTranslation whereProducerId($value)
 * @method static Builder|ProducerTranslation whereUpdatedAt($value)
 * @method static Builder|ProducerTranslation whereValue($value)
 * @mixin Eloquent
 */
class ProducerTranslation extends Translation
{
    protected $fillable = [
        'producer_id',
        'lang',
        'column',
        'value',
    ];
}
