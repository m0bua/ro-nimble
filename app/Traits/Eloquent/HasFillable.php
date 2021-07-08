<?php

namespace App\Traits\Eloquent;

use App\Casts\Translatable;

trait HasFillable
{
    /**
     * Returns all fillable attributes from Model
     *
     * @return array
     */
    public function getFillable(): array
    {
        $translatableCasts = $this->getTranslatableProperties();
        return array_filter($this->fillable, fn($key) => !in_array($key, $translatableCasts, true));
    }

    /**
     * @return array<string>
     */
    public function getTranslatableProperties(): array
    {
        return array_keys($this->casts, Translatable::class);
    }
}
