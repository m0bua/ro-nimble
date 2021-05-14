<?php

namespace App\Traits\Eloquent;

trait HasFillable
{
    /**
     * Returns all fillable attributes from Model
     *
     * @return array
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }
}
