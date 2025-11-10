<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Audio
{
    /**
     * @param  Id3Stream[]  $streams
     */
    protected function __construct(
        public readonly ?string $data_format = null,
        public readonly ?int $channels = null,
        public readonly ?int $sample_rate = null,
        public readonly ?float $bitrate = null,
        public readonly ?string $channel_mode = null,
        public readonly ?string $bitrate_mode = null,
        public readonly ?string $codec = null,
        public readonly ?string $encoder = null,
        public readonly bool $lossless = false,
        public readonly ?string $encoder_options = null,
        public readonly ?float $compression_ratio = null,
        public readonly array $streams = [],
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $streams = [];
        if (array_key_exists('streams', $metadata)) {
            foreach ($metadata['streams'] as $stream) {
                $streams[] = Id3Stream::make($stream);
            }
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
            streams: $streams,
        );

        return $self;
    }

    public function stream(): ?Id3Stream
    {
        return $this->streams[0] ?? null;
    }
}
