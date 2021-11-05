<?php

namespace App\Providers;

use App\Macros\TrueCursor;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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

        DB::listen(function ($query) {
            Log::channel('db_queries')->info('PostgreSQL Query',
                [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'executed_time' => $query->time,
                ]
            );
        });
    }
}
