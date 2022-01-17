<?php

namespace App\Filters\Contracts;

use Illuminate\Support\Collection;

interface Filterable
{
    /**
     * Returns filter name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns filter values
     *
     * @return array
     */
    public function getValues(): Collection;
}
