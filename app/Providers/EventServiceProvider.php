<?php

namespace AtlasVG\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Log\Events\MessageLogged' => [
            'AtlasVG\Listeners\MessageLoggedListener',
        ],
    ];
}
