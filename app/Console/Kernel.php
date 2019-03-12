<?php

namespace AtlasVG\Console;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * @var array
     */
    protected $commands = [
        \AtlasVG\Console\Commands\Import::class,
    ];
}
