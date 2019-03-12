<?php

namespace AlphaDeltas\Logger\Tests\Feature\Logging;

use App\Logging\PushUidProcessor;
use File;
use Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagMergeProcessorTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        if (File::exists(storage_path('logs/tag-merge-test.log'))) {
            File::delete(storage_path('logs/tag-merge-test.log'));
        }

        File::put(storage_path('logs/tag-merge-test.log'), '');

        config([
            'logging.channels' => [
                'tag-merge-test' => [
                    'driver' => 'single',
                    'path'   => storage_path('logs/tag-merge-test.log'),
                    'level'  => 'debug',
                ],
            ],
        ]);
    }

    public function testUidTheSameForDifferentChannels()
    {
        $this->markTestIncomplete('I do not know why this is not working. Figure out later.');

        pushTagsToLog(['some']);

        Log::stack(['tag-merge-test'])->info('Info message!');

        $tagLog = File::get(storage_path('logs/tag-merge-test.log'));

        $this->assertNotEmpty($tagLog);
        $this->assertTrue('');

        //$this->assertEquals($tagLog);

        File::delete([
            storage_path('logs/tag-merge-test.log'),
        ]);
    }
}
