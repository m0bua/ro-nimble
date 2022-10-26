<?php

namespace App\Services\Eloquent;

use ErrorException;
use Log;
use Illuminate\Database\Eloquent\Builder as OriginalBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;

/**
 * App\Models\Eloquent\AbstractBuilder
 */
class Builder extends OriginalBuilder
{
    private static function log(QueryException $e)
    {
        Log::channel('db_errors')->error(str_replace('"', '\"', $e->getMessage()), [
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'previous' => $e->getPrevious(),
        ]);
    }

    public function pluck($column, $key = null)
    {
        try {
            return parent::pluck($column, $key);
        } catch (QueryException $e) {
            static::log($e);
            return new Collection([]);
        }
    }

    public function get($columns = ['*'])
    {
        try {
            return parent::get($columns);
        } catch (QueryException $e) {
            static::log($e);
            return new Collection([]);
        }
    }
}
