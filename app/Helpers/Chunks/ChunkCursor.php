<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Helpers\Chunks;

use App\Interfaces\ChunkInterface;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChunkCursor implements ChunkInterface
{

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public static function iterate(Builder $query, Closure $callback, int $chunkSize = 500)
    {
        $sqlWithSlashes = str_replace('?', "'?'", $query->toSql());
        $preparedSql = vsprintf(str_replace('?', '%s', $sqlWithSlashes), $query->getBindings());
        $cursorName = "cursor_" . md5(microtime(true));

        DB::beginTransaction();
        DB::select("DECLARE $cursorName CURSOR FOR $preparedSql");

        do {
            $result = DB::select("FETCH $chunkSize FROM $cursorName");
            $resultCount = count($result);

            if ($resultCount == 0) {
                break;
            }

            try {
                $callback($result);
            } catch (Throwable $t) {
                Log::error($t->getMessage(), [
                    'file' => $t->getFile(),
                    'line' => $t->getLine()
                ]);
            }
        } while ($resultCount == $chunkSize);

        DB::select("CLOSE $cursorName");
        DB::commit();
    }
}
