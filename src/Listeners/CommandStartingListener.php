<?php 
declare(strict_types=1);

namespace AlphaDeltas\Logger\Listeners;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Log;

class CommandStartingListener
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Console\Events\CommandStarting $commandStartingEvent
     *
     * @return void
     */
    public function handle(CommandStarting $commandStartingEvent)
    {
        cleanLogTags();
        pushTagsToLog(['command']);

        Log::notice('Command has started.', [
            'signature'    => $commandStartingEvent->command,
            'passedParams' => (string)$commandStartingEvent->input,
        ]);
    }
}
