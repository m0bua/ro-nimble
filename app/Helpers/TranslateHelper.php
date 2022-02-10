<?php
/**
 * Class TranslateHelper
 * @package App\Helpers
 */

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class TranslateHelper
{
    /**
     * Создаем список переводов для фильтров
     * @param EloquentCollection $translations
     * @return void|string
     */
    public static function getTranslationFields(EloquentCollection $translations, string $translationField): Collection
    {
        return $translations->groupBy([$translationField, 'column', 'lang'])->map(function (Collection $column) {
            return $column->map(function (Collection $langs) {
                if ($lang = $langs->get(App::getLocale())) {
                    return $lang->first()->value;
                } elseif ($lang = $langs->get(config('translatable.default_language'))) {
                    return $lang->first()->value;
                }
            });
        });
    }
}
