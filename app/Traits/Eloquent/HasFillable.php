<?php

namespace App\Traits\Eloquent;

use App\Casts\Translatable;
use App\Casts\Regional;

trait HasFillable
{
    private static array $fillableTypes = [
        Translatable::class,
        Regional::class,
    ];

    /**
     * Returns all fillable attributes from Model
     *
     * @return array
     */
    public function getFillable(): array
    {
        $fillableCasts = $this->getFillableProperties();
        return array_filter($this->fillable, fn ($key) => !in_array($key, $fillableCasts, true));
    }

    /**
     * @return array<string>
     */
    public function getFillableProperties(?string $type = null): array
    {
        if (!empty($type)) {
            return array_keys($this->casts, $type);
        }

        $result = [];
        foreach (self::$fillableTypes as $type) {
            $result = array_merge($result, $this->getFillableProperties($type));
        }

        return array_unique($result);
    }
}
