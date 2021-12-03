<?php

namespace App\Providers;

use App\Macros\TrueCursor;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    protected $dbLogQueries = [
        'update',
        'delete'
    ];

    /**
     * Register any application services.
     *
     * @return void
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Eloquent\Builder::macro('trueCursor', function (int $count = 1) {
            return new TrueCursor($this, $count);
        });

        $this->logDbQueries();
    }

    /**
     *
     */
    protected function logDbQueries()
    {
        DB::listen(function ($query) {
            $queryMatches = [
                'delete',
                'update',
                'insert'
            ];

            $tableMatches = [
                'categories',
            ];

            foreach ($queryMatches as $queryMatch) {
                if (preg_match("/^$queryMatch.*$/", $query->sql)) {
                    foreach ($tableMatches as $tableMatch) {
                        if (preg_match("/\s+\"$tableMatch\"\s+/", $query->sql)) {
                            Log::channel('db_queries')->info('PostgreSQL Query',
                                [
                                    'sql' => $query->sql,
                                    'bindings' => $query->bindings,
                                    'executed_time' => $query->time,
                                ]
                            );
                        }
                    }
                }
            }
        });
    }
}
