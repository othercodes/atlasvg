<?php

namespace AtlasVG\Listeners;

use Illuminate\Log\Events\MessageLogged;
use Symfony\Component\Console\Output\ConsoleOutput;

class MessageLoggedListener
{
    /**
     * Handle the event.
     * @param MessageLogged $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        if (app()->runningInConsole()) {
            $output = new ConsoleOutput();
            $output->writeln($event->message);
        }
    }
}
