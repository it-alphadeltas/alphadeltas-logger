<?php 
declare(strict_types=1);

namespace AlphaDeltas\Logger\Listeners;

use Illuminate\Console\Events\CommandFinished;
use Log;

class CommandFinishedListener
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Console\Events\CommandFinished $commandTerminatingEvent
     *
     * @return void
     */
    public function handle(CommandFinished $commandTerminatingEvent)
    {
        Log::notice('Command has finished.', [
            'signature'     => $commandTerminatingEvent->command,
            'passedParams'  => (string)$commandTerminatingEvent->input,
            'executionTime' => App::environment('testing') ?: round(microtime(true) - LARAVEL_START, 2),
        ]);
    }
}
