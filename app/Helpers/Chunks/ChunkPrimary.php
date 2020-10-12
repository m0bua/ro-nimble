<?php

namespace App\Helpers\Chunks;

use App\Interfaces\ChunkInterface;
use App\Logging\CustomLogger;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;

class ChunkPrimary implements ChunkInterface
{
    /**
     * @inheritDoc
     */
    public static function iterate(Builder $query, \Closure $callback, int $chunkSize = 500)
    {
        $startId = 0;

        do {
            $result = $query
                ->where('main_table.id', '>', $startId)
                ->orderBy('primary_id')
                ->limit($chunkSize)
                ->get();

            $resultCount = $result->count();

            if ($resultCount == 0) {
                break;
            }

            $startId = $result->max('primary_id');

            try {
                $callback($result->toArray());
            } catch (\Throwable $t) {
                Log::channel('consumer')->warning(
                    CustomLogger::generateMessage($t)
                );
            }

        } while($resultCount == $chunkSize);
    }
}
