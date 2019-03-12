<?php
declare(strict_types=1);

use AlphaDeltas\Logger\Logging\Processors\TagMergeProcessor;

if (!function_exists('pushTagsToLog')) {
    /**
     * Pushes tags to your log.
     *
     * @param array $tags
     *
     * @return void
     */
    function pushTagsToLog(array $tags): void
    {
        //hot fix due to breaking changes
        //submitted an issue request
        //https://github.com/timacdonald/log-fake/issues/3
        $logManager = app('log');
        if (!($logManager instanceof \TiMacDonald\Log\LogFake)) {
            collect($logManager->getHandlers())->each(function ($handler) use ($tags) {
                $handler->pushProcessor(new TagMergeProcessor($tags));
            });
        }
    }
}

if (!function_exists('cleanLogTags')) {
    /**
     * Cleans tags from your log.
     * Rejects all TagMergeProcessor instances from processors.
     *
     * @see TagMergeProcessor
     *
     * @return void
     */
    function cleanLogTags(): void
    {
        //hot fix due to breaking changes
        //submitted an issue request
        //https://github.com/timacdonald/log-fake/issues/3
        $logManager = app('log');
        if (!($logManager instanceof \TiMacDonald\Log\LogFake)) {
            collect($logManager->getHandlers())->each(function ($handler) {
                $processors = collect();

                try {
                    while (true) {
                        $processors->push($handler->popProcessor());
                    }
                } catch (LogicException $e) {
                    //todo @dr create rejectClassInstance() macro or something like that
                    $processors = $processors->reject(function ($processor) {
                        return $processor instanceof TagMergeProcessor;
                    });
                }

                $processors->each(function ($processor) use ($handler) {
                    $handler->pushProcessor($processor);
                });
            });
        }
    }
}
