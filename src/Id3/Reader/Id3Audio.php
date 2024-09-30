<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Audio extends Id3AudioBase
{
    /** @var Id3Stream[] */
    protected array $streams = [];

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

        $self = new self($metadata);
        $self->streams = $streams;

        return $self;
    }

    /** @return Id3Stream[] */
    public function streams(): array
    {
        return $this->streams;
    }

    public function dataFormat(): ?string
    {
        return $this->data_format;
    }

    public function channels(): ?int
    {
        return $this->channels;
    }

    public function sampleRate(): ?int
    {
        return $this->sample_rate;
    }

    public function bitrate(): ?float
    {
        return $this->bitrate;
    }

    public function channelMode(): ?string
    {
        return $this->channel_mode;
    }

    public function bitrateMode(): ?string
    {
        return $this->bitrate_mode;
    }

    public function codec(): ?string
    {
        return $this->codec;
    }

    public function encoder(): ?string
    {
        return $this->encoder;
    }

    public function lossless(): bool
    {
        return $this->lossless;
    }

    public function encoderOptions(): ?string
    {
        return $this->encoder_options;
    }

    public function compressionRatio(): ?float
    {
        return $this->compression_ratio;
    }

    public function stream(): ?Id3Stream
    {
        return $this->streams[0] ?? null;
    }
}
