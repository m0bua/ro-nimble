<?php

namespace App\Providers;

use App\Filters\Contracts\FiltersInterface;
use App\Filters\Filters;
use App\Http\Requests\FilterRequest;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Filters::class, function (Application $app) {
            $request = $app->make(FilterRequest::class);
            return Filters::fromRequest($request);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
