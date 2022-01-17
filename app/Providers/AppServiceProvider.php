<?php

namespace App\Providers;

use App\Macros\SelectNestedTranslation;
use App\Macros\SelectTranslation;
use App\Macros\TrueCursor;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Collection;
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
        $this->collectionMacros();
    }

    /**
     * Declare query logging
     */
    protected function translatableMacros(): void
    {
        Eloquent\Builder::macro('selectTranslation', function (string $field, string $alias = '', string $lang = ''): Eloquent\Builder {
            return (new SelectTranslation($this, $field, $alias, $lang))->build();
        });

        Eloquent\Builder::macro('selectTranslations', function (array $fields, string $lang = ''): Eloquent\Builder {
            $query = $this;

            foreach ($fields as $alias => $field) {
                $query = (new SelectTranslation($query,
                    $field,
                    is_string($alias) ? $alias : '',
                    $lang))->build();
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

            foreach ($fields as $alias => $field) {
                $query = (new SelectNestedTranslation(
                    $this,
                    $targetClass,
                    $field,
                    is_string($alias) ? $alias : '',
                    $targetClassAlias,
                    $lang))->build();
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

    /**
     * Base collection macros
     *
     * @return void
     */
    protected function collectionMacros(): void
    {
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });
    }
}
