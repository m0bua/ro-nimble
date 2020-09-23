<?php


namespace App\Console\Commands\Extend;


use App\Logging\CustomLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CustomCommand extends Command
{
    protected function catchExceptions(\Closure $callback)
    {
        try {
            $callback();
        } catch (\Throwable $t) {
            Log::channel('consumer')->warning(
                CustomLogger::generateMessage($t)
            );
        }
    }
}
