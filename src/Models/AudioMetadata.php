<?php

namespace Kiwilan\Audio\Models;

use Kiwilan\Audio\Audio;

class AudioMetadata
{
    protected function __construct(
        protected ?int $filesize = null,
        protected ?string $extension = null,
        protected ?string $encoding = null,
        protected ?string $mimeType = null,
        protected ?float $durationSeconds = null,
        protected ?string $durationReadable = null,
        protected ?int $bitrate = null,
        protected ?string $bitrateMode = null,
        protected ?int $sampleRate = null,
        protected ?int $channels = null,
        protected ?string $channelMode = null,
        protected bool $lossless = false,
        protected ?float $compressionRatio = null,
    ) {
    }

    public static function make(Audio $audio): self
    {
        $reader = $audio->getReader();
        $audio = $reader->getAudio();

        return new self(
            filesize: $reader->getFilesize(),
            extension: $audio?->dataformat(),
            encoding: $reader->getEncoding(),
            mimeType: $reader->getMimeType(),
            durationSeconds: $reader->getPlaytimeSeconds(),
            durationReadable: $reader->getPlaytimeString(),
            bitrate: $reader->getBitrate(),
            bitrateMode: $audio?->bitrate_mode(),
            sampleRate: $audio?->sample_rate(),
            channels: $audio?->channels(),
            channelMode: $audio?->channelmode(),
            lossless: $audio?->lossless() ?? false,
            compressionRatio: $audio?->compression_ratio(),
        );
    }

    public function getFilesize(): ?int
    {
        return $this->filesize;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getDurationSeconds(): ?float
    {
        return $this->durationSeconds;
    }

    public function getDurationReadable(): ?string
    {
        return $this->durationReadable;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    public function getBitrateMode(): ?string
    {
        return $this->bitrateMode;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    public function getChannels(): ?int
    {
        return $this->channels;
    }

    public function getChannelMode(): ?string
    {
        return $this->channelMode;
    }

    public function getLossless(): bool
    {
        return $this->lossless;
    }

    public function getCompressionRatio(): ?float
    {
        return $this->compressionRatio;
    }
}
