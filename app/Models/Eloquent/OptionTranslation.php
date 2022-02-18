<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

/**
 * App\Models\Eloquent\OptionTranslation
 *
 * @property int $id
 * @property int|null $option_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Option $entity
 * @method static Builder|OptionTranslation newModelQuery()
 * @method static Builder|OptionTranslation newQuery()
 * @method static Builder|OptionTranslation query()
 * @method static Builder|OptionTranslation whereColumn($value)
 * @method static Builder|OptionTranslation whereCreatedAt($value)
 * @method static Builder|OptionTranslation whereId($value)
 * @method static Builder|OptionTranslation whereLang($value)
 * @method static Builder|OptionTranslation whereOptionId($value)
 * @method static Builder|OptionTranslation whereUpdatedAt($value)
 * @method static Builder|OptionTranslation whereValue($value)
 * @mixin Eloquent
 */
class OptionTranslation extends Translation
{
    protected $fillable = [
        'option_id',
        'lang',
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
            ->withLang()
            ->get();
    }
}
