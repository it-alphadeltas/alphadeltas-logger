<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Providers;

use AlphaDeltas\Grampa\Middleware\AuthGrampa;
use AlphaDeltas\Logger\Listeners\CommandFinishedListener;
use AlphaDeltas\Logger\Listeners\CommandStartingListener;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;
use Log;
use Queue;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /** @see \Illuminate\Console\Command */
        CommandStarting::class => [
            CommandStartingListener::class,
        ],
        CommandFinished::class => [
            CommandFinishedListener::class,
        ],
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            cleanLogTags();
            pushTagsToLog(['job', $event->job->getQueue(), strtolower(str_random(7))]);

            Log::notice('Job has started.', [
                'job' => $event->job->payload()['displayName'],
            ]);
        });

        Queue::failing(function (JobFailed $event) {
            Log::error('Job has failed.', [
                'job'       => $event->job->resolveName(),
                'exception' => [
                    'class'   => get_class($event->exception),
                    'file'    => $event->exception->getFile() . ':' . $event->exception->getLine(),
                    'message' => $event->exception->getMessage(),
                    'trace'   => $event->exception->getTraceAsString(),
                ],
            ]);
        });

        Queue::after(function (JobProcessed $event) {
            Log::notice('Job has finished.', [
                'job' => $event->job->payload()['displayName'],
            ]);
        });

        $this->publishes([
            __DIR__ . '/../resources/config' => config_path('logger'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Merge options with published config
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/routes.php', 'logger.routes');
    }
}
