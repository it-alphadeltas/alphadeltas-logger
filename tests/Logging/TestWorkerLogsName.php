<?php

namespace AlphaDeltas\Logger\Tests\Feature\Logging;

use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tests\TestCase;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        //do nothing
    }
}

class TestWorkerLogsName extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        if (File::exists(storage_path('logs/test-worker-logging-name.log'))) {
            File::delete(storage_path('logs/test-worker-logging-name.log'));
        }

        File::put(storage_path('logs/test-worker-logging-name.log'), '');

        config([
            'logging.default'  => 'test-worker-logging-name',
            'logging.channels' => [
                'test-worker-logging-name' => [
                    'driver' => 'single',
                    'path'   => storage_path('logs/test-worker-logging-name.log'),
                    'level'  => 'debug',
                ],
            ],
        ]);
    }

    public function testWorkerLogsName()
    {
        TestJob::dispatch()->onQueue('test-worker-logging-name');

        $this->artisan('queue:work', [
            '--queue' => 'test-worker-logging-name',
            '--once'  => true,
        ]);

        $tagLog = File::get(storage_path('logs/test-worker-logging-name.log'));

        $this->assertNotEmpty($tagLog);
        collect(explode("\n", $tagLog))->slice(1, 3)->each(function (string $line) {
            $this->assertTrue(str_contains($line, 'test-worker-logging-name'));
        });
    }

    public function testWorkerLogsDefaultQueueName()
    {
        TestJob::dispatch();

        $this->artisan('queue:work', [
            '--once' => true,
        ]);

        $tagLog = File::get(storage_path('logs/test-worker-logging-name.log'));

        $this->assertNotEmpty($tagLog);
        collect(explode("\n", $tagLog))->slice(1, 3)->each(function (string $line) {
            $this->assertTrue(str_contains($line, 'default'));
        });
    }

    public function testWorkerForMultipleQueuesLogsQueues()
    {
        TestJob::dispatch()->onQueue('first-test-worker-logging-name');
        TestJob::dispatch()->onQueue('second-test-worker-logging-name');

        $this->artisan('queue:work', [
            '--queue' => 'first-test-worker-logging-name,second-test-worker-logging-name',
            '--once'  => true,
        ]);
        $this->artisan('queue:work', [
            '--queue' => 'first-test-worker-logging-name,second-test-worker-logging-name',
            '--once'  => true,
        ]);

        $tagLog = File::get(storage_path('logs/test-worker-logging-name.log'));

        $this->assertNotEmpty($tagLog);
        collect(explode("\n", $tagLog))->slice(1, 3)->each(function (string $line) {
            $this->assertTrue(str_contains($line, 'first-test-worker-logging-name'));
        });
        collect(explode("\n", $tagLog))->slice(5, 3)->each(function (string $line) {
            $this->assertTrue(str_contains($line, 'second-test-worker-logging-name'));
        });
    }
}
