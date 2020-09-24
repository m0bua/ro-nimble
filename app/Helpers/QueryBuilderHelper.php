<?php


namespace App\Helpers;


use Illuminate\Database\Query\Builder;

class QueryBuilderHelper
{
    public static function chunk(int $limit, Builder $query, \Closure $callback)
    {
        $offset = 0;
        do {
            $result = $query->limit($limit)->offset($offset)->get();
            $resultCount = $result->count();

            if ($resultCount == 0) {
                break;
            }

            $callback($result);

            $offset = $offset + $limit;

        } while ($resultCount == $limit);
    }
}
