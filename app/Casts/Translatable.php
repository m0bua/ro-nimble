<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Translatable implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|null
     */
    public function get($model, string $key, $value, array $attributes): ?string
    {
        return $value ?? (method_exists($model, 'getTranslation')
                ? $model->getTranslation($key, App::getLocale())
                : $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param array|string $value
     * @param array $attributes
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value) && method_exists($model, 'setTranslations')) {
            $model->setTranslations($key, $value);
            return;
        }

        if (method_exists($model, 'setTranslation')) {
            $model->setTranslation(App::getLocale(), $key, $value);
        }
    }
}
