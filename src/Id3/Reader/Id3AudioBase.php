<?php

namespace Kiwilan\Audio\Id3\Reader;

abstract class Id3AudioBase
{
    protected ?string $data_format = null;

    protected ?int $channels = null;

    protected ?int $sample_rate = null;

    protected ?float $bitrate = null;

    protected ?string $channel_mode = null;

    protected ?string $bitrate_mode = null;

    protected ?string $codec = null;

    protected ?string $encoder = null;

    protected bool $lossless = false;

    protected ?string $encoder_options = null;

    protected ?float $compression_ratio = null;

    protected function __construct(?array $metadata)
    {
        if (! $metadata) {
            return;
        }

        $this->data_format = $metadata['dataformat'] ?? null;
        $this->channels = $metadata['channels'] ?? null;
        $this->sample_rate = $metadata['sample_rate'] ?? null;
        $this->bitrate = $metadata['bitrate'] ?? null;
        $this->channel_mode = $metadata['channelmode'] ?? null;
        $this->bitrate_mode = $metadata['bitrate_mode'] ?? null;
        $this->codec = $metadata['codec'] ?? null;
        $this->encoder = $metadata['encoder'] ?? null;
        $this->lossless = $metadata['lossless'] ?? false;
        $this->encoder_options = $metadata['encoder_options'] ?? null;
        $this->compression_ratio = $metadata['compression_ratio'] ?? null;
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
}
