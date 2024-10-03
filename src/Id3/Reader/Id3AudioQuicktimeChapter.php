<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3AudioQuicktimeChapter
{
    protected function __construct(
        protected float|int|null $timestamp = null,
        protected ?string $title = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $timestamp = $metadata['timestamp'] ?? null;
        $title = $metadata['title'] ?? null;

        $self = new self(
            timestamp: $timestamp,
            title: $title,
        );

        return $self;
    }

    public function getTimestamp(): float|int|null
    {
        return $this->timestamp;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function toArray(): array
    {
        return [
            'timestamp' => $this->timestamp,
            'title' => $this->title,
        ];
    }
}
