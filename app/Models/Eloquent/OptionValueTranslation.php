<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\OptionValueTranslation
 *
 * @property int $id
 * @property int|null $option_value_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read OptionValue $entity
 * @method static Builder|OptionValueTranslation newModelQuery()
 * @method static Builder|OptionValueTranslation newQuery()
 * @method static Builder|OptionValueTranslation query()
 * @method static Builder|OptionValueTranslation whereColumn($value)
 * @method static Builder|OptionValueTranslation whereCreatedAt($value)
 * @method static Builder|OptionValueTranslation whereId($value)
 * @method static Builder|OptionValueTranslation whereLang($value)
 * @method static Builder|OptionValueTranslation whereOptionValueId($value)
 * @method static Builder|OptionValueTranslation whereUpdatedAt($value)
 * @method static Builder|OptionValueTranslation whereValue($value)
 * @mixin Eloquent
 */
class OptionValueTranslation extends Translation
{
    protected $fillable = [
        'option_value_id',
        'lang',
        'column',
        'value',
    ];

    /**
     * @param array $ids
     * @return Collection
     */
    public static function getByOptionValueIds(array $ids): Collection
    {
        return static::whereIn('option_value_id', $ids)
            ->withLang()
            ->get();
    }
}
