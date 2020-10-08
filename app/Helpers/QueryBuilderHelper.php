<?php


namespace App\Helpers;


use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class QueryBuilderHelper
{
    public static function chunk(Builder $query, \Closure $callback, int $chunkSize = 500)
    {
        $addSlashes = str_replace('?', "'?'", $query->toSql());
        $sql = vsprintf(str_replace('?', '%s', $addSlashes), $query->getBindings());
        $cursorName = "cursor_" . md5(microtime(true));

        DB::beginTransaction();
        DB::select("DECLARE {$cursorName} CURSOR FOR {$sql}");

        do {
            $result = DB::select("FETCH {$chunkSize} FROM {$cursorName}");
            $resultCount = count($result);

            if ($resultCount == 0) {
                break;
            }

            $callback($result);

        } while($resultCount == $chunkSize);

        DB::select("CLOSE {$cursorName}");
        DB::commit();
    }

    public static function chunkByPrimary(Builder $query, \Closure $callback, int $chunkSize = 3)
    {
        $startId = 0;

        do {
            $result = $query
                ->where('main_table.id', '>', $startId)
                ->orderBy('primary_id', 'asc')
                ->limit($chunkSize)
                ->get();

            $resultCount = count($result);

            if ($resultCount == 0) {
                break;
            }

            $startId = $result->max('primary_id');

            $callback($result->toArray());
        } while($resultCount == $chunkSize);
    }
}
