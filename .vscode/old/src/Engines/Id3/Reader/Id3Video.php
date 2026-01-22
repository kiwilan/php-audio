<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Video
{
    protected function __construct(
        public readonly ?string $data_format = null,
        public readonly ?int $rotate = null,
        public readonly ?float $resolution_x = null,
        public readonly ?float $resolution_y = null,
        public readonly ?float $frame_rate = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            data_format: $metadata['dataformat'] ?? null,
            rotate: $metadata['rotate'] ?? null,
            resolution_x: $metadata['resolution_x'] ?? null,
            resolution_y: $metadata['resolution_y'] ?? null,
            frame_rate: $metadata['frame_rate'] ?? null,
        );

        return $self;
    }
}
