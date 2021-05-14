<?php

namespace App\Providers;

use App\Events\ExampleEvent;
use App\Listeners\ExampleListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ExampleEvent::class => [
            ExampleListener::class,
        ],
    ];
}
