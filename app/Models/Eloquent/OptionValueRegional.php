<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Eloquent\OptionSettingRegional
 *
 * @property int $id
 * @property int|null $option_setting_id
 * @property string $country
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $need_delete
 * @property-read OptionSetting $entity
 * @method static Builder|OptionSettingRegional newModelQuery()
 * @method static Builder|OptionSettingRegional newQuery()
 * @method static Builder|OptionSettingRegional query()
 * @method static Builder|OptionSettingRegional whereColumn($value)
 * @method static Builder|OptionSettingRegional whereCreatedAt($value)
 * @method static Builder|OptionSettingRegional whereId($value)
 * @method static Builder|OptionSettingRegional whereCountry($value)
 * @method static Builder|OptionSettingRegional whereOptionSettingId($value)
 * @method static Builder|OptionSettingRegional whereUpdatedAt($value)
 * @method static Builder|OptionSettingRegional whereValue($value)
 * @mixin Eloquent
 */
class OptionValueRegional extends Regional
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
    public static function getByOptionValueIds(array $ids): Collection
    {
        return static::whereIn('option_value_id', $ids)
            ->withCountry()
            ->get();
    }
}
