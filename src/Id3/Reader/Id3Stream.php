<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Stream
{
    protected function __construct(
        readonly public ?string $data_format = null,
        readonly public ?int $channels = null,
        readonly public ?int $sample_rate = null,
        readonly public ?float $bitrate = null,
        readonly public ?string $channel_mode = null,
        readonly public ?string $bitrate_mode = null,
        readonly public ?string $codec = null,
        readonly public ?string $encoder = null,
        readonly public bool $lossless = false,
        readonly public ?string $encoder_options = null,
        readonly public ?float $compression_ratio = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            data_format: $metadata['dataformat'] ?? null,
            channels: $metadata['channels'] ?? null,
            sample_rate: $metadata['sample_rate'] ?? null,
            bitrate: $metadata['bitrate'] ?? null,
            channel_mode: $metadata['channelmode'] ?? null,
            bitrate_mode: $metadata['bitrate_mode'] ?? null,
            codec: $metadata['codec'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            lossless: $metadata['lossless'] ?? false,
            encoder_options: $metadata['encoder_options'] ?? null,
            compression_ratio: $metadata['compression_ratio'] ?? null,
        );

        return $self;
    }
}
