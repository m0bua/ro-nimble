<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateModelMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:model-meta {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Short way to generate Model's meta";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $model = $this->argument('model') ?? $this->anticipate("Model's name", $this->getModelNames());

        $this->call('ide-helper:models', [
            'model' => ["App\Models\\Eloquent\\$model"],
            '--smart-reset' => true,
            '--write' => true,
        ]);

        return 0;
    }

    private function getModelNames(): array
    {
        $dir = app_path('Models/Eloquent');
        $files = collect(scandir($dir));

        return $files
            ->filter(fn(string $fileName) => $fileName !== '.' && $fileName !== '..')
            ->map(fn(string $fileName) => Str::beforeLast($fileName, '.'))
            ->toArray();
    }
}
