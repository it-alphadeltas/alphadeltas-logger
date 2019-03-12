<?php

namespace AlphaDeltas\Logger\Tests\Feature\Logging;

use App\Logging\PushUidProcessor;
use File;
use Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UidProcessorTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        if (File::exists(storage_path('logs/first.log'))) {
            File::delete(storage_path('logs/first.log'));
        }
        if (File::exists(storage_path('logs/second.log'))) {
            File::delete(storage_path('logs/second.log'));
        }

        File::put(storage_path('logs/first.log'), '');
        File::put(storage_path('logs/second.log'), '');

        config([
            'logging.channels' => [
                'first'  => [
                    'driver' => 'single',
                    'path'   => storage_path('logs/first.log'),
                    'tap'    => [PushUidProcessor::class],
                    'level'  => 'debug',
                ],
                'second' => [
                    'driver' => 'single',
                    'path'   => storage_path('logs/second.log'),
                    'tap'    => [PushUidProcessor::class],
                    'level'  => 'debug',
                ],
            ],
        ]);
    }

    public function testUidTheSameForDifferentChannels()
    {
        Log::stack(['first', 'second'])->info('Info message!');

        $first  = File::get(storage_path('logs/first.log'));
        $second = File::get(storage_path('logs/second.log'));

        $this->assertNotEmpty($first);
        $this->assertNotEmpty($second);
        $this->assertEquals($first, $second);

        File::delete([
            storage_path('logs/first.log'),
            storage_path('logs/second.log'),
        ]);
    }
}
