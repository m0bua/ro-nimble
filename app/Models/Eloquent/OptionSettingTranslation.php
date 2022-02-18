<?php

namespace App\Models\Eloquent;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * App\Models\Eloquent\OptionSettingTranslation
 *
 * @property int $id
 * @property int|null $option_setting_id
 * @property string $lang
 * @property string $column
 * @property string $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read OptionSetting $entity
 * @method static Builder|OptionSettingTranslation newModelQuery()
 * @method static Builder|OptionSettingTranslation newQuery()
 * @method static Builder|OptionSettingTranslation query()
 * @method static Builder|OptionSettingTranslation whereColumn($value)
 * @method static Builder|OptionSettingTranslation whereCreatedAt($value)
 * @method static Builder|OptionSettingTranslation whereId($value)
 * @method static Builder|OptionSettingTranslation whereLang($value)
 * @method static Builder|OptionSettingTranslation whereOptionSettingId($value)
 * @method static Builder|OptionSettingTranslation whereUpdatedAt($value)
 * @method static Builder|OptionSettingTranslation whereValue($value)
 * @mixin Eloquent
 */
class OptionSettingTranslation extends Translation
{
    /**
     * @param array $ids
     * @return Collection
     */
    public static function getByOptionSettingIds(array $ids): Collection
    {
        return static::whereIn('option_setting_id', $ids)
            ->withLang()
            ->get();
    }
}
