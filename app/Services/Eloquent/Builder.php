<?php

namespace App\Services\Eloquent;

use Log;
use Illuminate\Database\Eloquent\Builder as OriginalBuilder;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * App\Models\Eloquent\AbstractBuilder
 */
class Builder extends OriginalBuilder
{
    private static function log(QueryException $error, $message = 'SQL failture.')
    {
        Log::channel('db_errors')->error(str_replace('"', '\"', $error->getMessage()), [
            'sql' => $error->getSql(),
            'bindings' => $error->getBindings(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'previous' => $error->getPrevious(),
        ]);
        throw new HttpException(500, $message);
    }

    public function pluck($column, $key = null)
    {
        try {
            return parent::pluck($column, $key);
        } catch (QueryException $e) {
            static::log($e, 'SQL pluck failture.');
        }
    }

    public function get($columns = ['*'])
    {
        try {
            return parent::get($columns);
        } catch (QueryException $e) {
            static::log($e, 'SQL get failture.');
        }
    }
}
