<?php
declare(strict_types=1);

namespace AlphaDeltas\Logger\Logging\Processors;

/**
 * Merges a tags array into the log record.
 */
class TagMergeProcessor
{
    private $tags;

    public function __construct(array $tags = [])
    {
        $this->setTags($tags);
    }

    public function setTags(array $tags = []): void
    {
        $this->tags = $tags;
    }

    public function __invoke(array $record): array
    {
        $record['extra']['tags'] = collect(array_get($record, 'extra.tags'))->merge($this->tags)->unique()->toArray();
        return $record;
    }
}
