<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * App\Models\Eloquent\OptionRegional
 *
 * @property int $id
 * @property int|null $option_id
 * @property string $country
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read Option $entity
 * @method static Builder|OptionRegional newModelQuery()
 * @method static Builder|OptionRegional newQuery()
 * @method static Builder|OptionRegional query()
 * @method static Builder|OptionRegional whereColumn($value)
 * @method static Builder|OptionRegional whereCreatedAt($value)
 * @method static Builder|OptionRegional whereId($value)
 * @method static Builder|OptionRegional whereCountry($value)
 * @method static Builder|OptionRegional whereOptionId($value)
 * @method static Builder|OptionRegional whereUpdatedAt($value)
 * @method static Builder|OptionRegional whereValue($value)
 * @mixin Eloquent
 */
class OptionRegional extends Regional
{
    protected $fillable = [
        'option_id',
        'country',
        'column',
        'value',
    ];

    /**
     * @param array $ids
     * @return Collection
     */
    public static function getByOptionIds(array $ids): Collection
    {
        return static::query()->whereIn('option_id', $ids)
            ->withCountry()
            ->get();
    }
}
