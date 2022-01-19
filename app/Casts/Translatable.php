<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class Translatable implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return method_exists($model, 'getTranslations')
            ? $model->getTranslations($key)
            : $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ((is_array($value) || $value instanceof stdClass) && method_exists($model, 'setTranslations')) {
            return $model->setTranslations($key, (array)$value);
        }

        if (method_exists($model, 'setTranslation')) {
            return $model->setTranslation(config('translatable.default_language'), $key, $value);
        }

        return $value;
    }
}
