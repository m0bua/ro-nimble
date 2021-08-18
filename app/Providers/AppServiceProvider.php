<?php

namespace App\Providers;

use App\Macros\SelectNestedTranslation;
use App\Macros\SelectTranslation;
use App\Macros\TrueCursor;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected array $dbLogQueries = [
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
        $this->translatableMacros();
    }

    /**
     * Declare macro for fetch translations
     */
    protected function translatableMacros(): void
    {
        Eloquent\Builder::macro('selectTranslation', function (string $field, string $alias = '', string $lang = ''): Eloquent\Builder {
            return (new SelectTranslation($this, $field, $alias, $lang))->build();
        });

        Eloquent\Builder::macro('selectTranslations', function (array $fields, string $lang = ''): Eloquent\Builder {
            $query = $this;

            foreach ($fields as $field) {
                $query = (new SelectTranslation($query, $field, '', $lang))->build();
            }

            return $query;
        });

        Eloquent\Builder::macro('selectNestedTranslation', function (
            string $targetClass,
            string $field,
            string $alias = '',
            string $targetClassAlias = '',
            string $lang = ''
        ): Eloquent\Builder {
            return (new SelectNestedTranslation($this, $targetClass, $field, $alias, $targetClassAlias, $lang))->build();
        });

        Eloquent\Builder::macro('selectNestedTranslations', function (
            string $targetClass,
            array  $fields,
            string $targetClassAlias = '',
            string $lang = ''
        ): Eloquent\Builder {
            $query = $this;

            foreach ($fields as $field) {
                $query = (new SelectNestedTranslation($this, $targetClass, $field, '', $targetClassAlias, $lang))->build();
            }

            return $query;
        });
    }

    /**
     * Declare query logging
     */
    protected function logDbQueries(): void
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
