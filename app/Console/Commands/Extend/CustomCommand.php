<?php


namespace App\Console\Commands\Extend;


use App\Logging\CustomLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CustomCommand extends Command
{
    /**
     * @param \Closure $callback
     * @param bool $abortWhenError
     */
    protected function catchExceptions(\Closure $callback, bool $abortWhenError = false)
    {
        try {
            $callback();
        } catch (\Throwable $t) {
            Log::channel('consumer')->warning(
                CustomLogger::generateMessage($t)
            );

            if ($abortWhenError) {
                abort(500, "Error: {$t->getMessage()}. File: {$t->getFile()}. Line: {$t->getLine()}");
            }
        }
    }
}
