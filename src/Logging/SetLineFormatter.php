<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class SetLineFormatter
{
    /**
     * Sets line formatter.
     *
     * @param  \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        collect($logger->getHandlers())->each(function ($handler) {
            $handler->setFormatter(
                new LineFormatter("[%datetime%] %level_name%: %message% {\"extra\": %extra%, \"context\": %context%}\n")
            );
        });
    }
}
