<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Logging;

use Illuminate\Log\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;

class PushUidProcessor
{
    /**
     * Pushed uid processor for adding a unique identifier into records.
     *
     * @param  \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        collect($logger->getHandlers())->each(function ($handler) {
            $handler->pushProcessor(app(UidProcessor::class));
        });
    }
}
