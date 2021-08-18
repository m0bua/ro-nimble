<?php

namespace App\Console\Commands;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Command extends BaseCommand
{
    public function handle(): int
    {
        try {
            $this->proceed();
            $this->info('Executed successfully');
        } catch (Throwable $t) {
            Log::error($t->getMessage(), [
                'file' => $t->getFile(),
                'line' => $t->getLine(),
                'signature' => $this->signature,
            ]);
            $this->error('An error occurred.');
            $this->line($t->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * Proceed command execution
     *
     * @return void
     */
    protected function proceed(): void
    {
        //
    }
}
