<?php
/**
 * Class RegionalHelper
 * @package App\Helpers
 */

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class RegionalHelper
{
    /**
     * Создаем список переводов для фильтров
     * @param EloquentCollection $regCollection
     * @param string $regField
     * @return void|string
     */
    public static function getRegionalFields(EloquentCollection $regCollection, string $regField): Collection
    {
        return $regCollection->groupBy([$regField, 'column', 'country'])->map(function (Collection $column) {
            return $column->map(function (Collection $langs) {
                if ($lang = $langs->get(CountryHelper::getRequestCountry())) {
                    return $lang->first()->value;
                }
            });
        });
    }
}
