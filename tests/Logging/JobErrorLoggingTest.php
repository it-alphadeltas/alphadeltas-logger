<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Tests\Feature\Logging;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Log;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;

/**
 * Don't move, cause we are checking lines in this test too :)
 */
class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public function handle()
    {
        throw new InvalidArgumentException('Test');
    }
}

class JobErrorLoggingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_log_error_on_job_fail()
    {
        Log::swap(new LogFake);

        TestJob::dispatch();

        $this->artisan('queue:work', [
            '--once' => true,
        ]);

        Log::assertLogged('error', function ($message, $context) {
            return str_contains($message, 'Job has failed.')
                && $context == [
                    'job'       => 'Tests\Feature\Logging\TestJob',
                    'exception' => [
                        'class'   => 'InvalidArgumentException',
                        'file'    => '/var/www/tests/Feature/Logging/JobErrorLoggingTest.php:28',
                        'message' => 'Test',
                        'trace'   => $context['exception']['trace'], //don't care about trace that much
                    ],
                ];
        });
    }
}
