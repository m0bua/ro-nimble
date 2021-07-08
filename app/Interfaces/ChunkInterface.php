<?php

namespace App\Interfaces;

use Closure;
use Illuminate\Database\Query\Builder;

interface ChunkInterface
{
    /**
     * Iterates rows by chunk depends on chunkSize
     *
     * @param Builder $query
     * @param Closure $callback
     * @param int $chunkSize
     * @return mixed
     */
    public static function iterate(Builder $query, Closure $callback, int $chunkSize = 500);
}
