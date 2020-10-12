<?php

namespace App\Console\Commands\Extend;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExtCommand extends Command
{
    public function handle()
    {
        try {
            $this->extHandle();
        } catch (\Throwable $t) {
            Log::error($t->getMessage(), [
                'file' => $t->getFile(),
                'line' => $t->getLine(),
                'signature' => $this->signature,
            ]);

//            abort(500, "Error: {$t->getMessage()}. File: {$t->getFile()}. Line: {$t->getLine()}");
        }
    }

    protected function extHandle() {}
}
