<?php

namespace App\Casts;

use App\Helpers\CountryHelper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Regional implements CastsAttributes
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
        return $value ?? (method_exists($model, 'getRegional')
                ? $model->getRegional($key, CountryHelper::getRequestCountry())
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
        if (is_array($value) && method_exists($model, 'setRegionals')) {
            $model->setRegionals($key, $value);
            return;
        }

        if (method_exists($model, 'setRegional')) {
            $model->setRegional(CountryHelper::getRequestCountry(), $key, $value);
        }
    }
}
