<?php

namespace App\Console\Commands\Seed;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputOption;

/**
 * Клас для генерації дампа частини БД в json файлах
 */
class Seed extends SeedCommand
{
    /**
     * Execute the console command.
     * @example php artisan db:seed --tables=goods --tables=bonuses --main_dump=false
     * @return int
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $previousConnection = $this->resolver->getDefaultConnection();

        $this->resolver->setDefaultConnection($this->getDatabase());

        Model::unguarded(function () {
            $path = \filter_var($this->option('main_dump'), FILTER_VALIDATE_BOOL)
                ? config('filesystems.main_local_dump_path')
                : config('filesystems.secondary_local_dump_path');

            $this->getSeeder()->__invoke([
                $path,
                $this->option('tables')
            ]);
        });

        if ($previousConnection) {
            $this->resolver->setDefaultConnection($previousConnection);
        }

        $this->info('Database seeding completed successfully.');

        return 0;
    }

    /**
     * Get a seeder instance from the container.
     *
     * @return \Illuminate\Database\Seeder
     */
    protected function getSeeder()
    {
        return $this->laravel->make(DatabaseSeeder::class)
            ->setContainer($this->laravel)
            ->setCommand($this);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],
            ['main_dump', null, InputOption::VALUE_OPTIONAL, 'Use main/secondary dump', true],
            ['tables', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Tables', []],
        ];
    }
}
