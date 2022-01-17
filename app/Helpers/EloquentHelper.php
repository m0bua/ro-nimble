<?php
/**
 * Class EloquentHelper
 * @package App\Helpers
 */

namespace App\Helpers;

class EloquentHelper
{
    /**
     * Помощник для дебага sql запросов
     * @param $query
     * @return string
     */
    public static function getQueryWithBindings($query): string
    {
        return vsprintf(
            str_replace('?', '%s', $query->toSql()),
            collect($query->getBindings())->map(function ($binding) {
                $binding = addslashes($binding);

                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray()
        );
    }
}
