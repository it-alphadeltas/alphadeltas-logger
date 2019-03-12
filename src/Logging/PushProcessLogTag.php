<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Logging;

use Illuminate\Log\Logger;
use Monolog\Processor\TagProcessor;

class PushProcessLogTag
{
    /**
     * @param  \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        collect($logger->getHandlers())->each(function ($handler) {
            $handler->pushProcessor(new TagProcessor(['process-log']));
        });
    }
}
