<?php

namespace Kiwilan\Audio\Models;

class AudioMetadata
{
    protected function __construct(
        protected ?int $filesize = null,
        protected ?string $extension = null,
        protected ?string $encoding = null,
        protected ?string $mimeType = null,
        protected ?float $playtimeInSeconds = null,
        protected ?string $playtimeHumanReadable = null,
        protected ?int $bitrate = null,
        protected ?string $bitrateMode = null,
        protected ?int $sampleRate = null,
        protected ?int $channels = null,
        protected ?string $channelMode = null,
        protected bool $lossless = false,
        protected ?float $compressionRatio = null,
    ) {
    }

    public static function make(Id3Item $item): self
    {
        $audio = $item->audio();

        return new self(
            filesize: $item->filesize(),
            extension: $audio->dataformat(),
            encoding: $item->encoding(),
            mimeType: $item->mime_type(),
            playtimeInSeconds: $item->playtime_seconds(),
            playtimeHumanReadable: $item->playtime_string(),
            bitrate: $item->bitrate(),
            bitrateMode: $audio->bitrate_mode(),
            sampleRate: $audio->sample_rate(),
            channels: $audio->channels(),
            channelMode: $audio->channelmode(),
            lossless: $audio->lossless(),
            compressionRatio: $audio->compression_ratio(),
        );
    }

    public function filesize(): ?int
    {
        return $this->filesize;
    }

    public function extension(): ?string
    {
        return $this->extension;
    }

    public function encoding(): ?string
    {
        return $this->encoding;
    }

    public function mimeType(): ?string
    {
        return $this->mimeType;
    }

    public function playtimeInSeconds(): ?float
    {
        return $this->playtimeInSeconds;
    }

    public function playtimeHumanReadable(): ?string
    {
        return $this->playtimeHumanReadable;
    }

    public function bitrate(): ?int
    {
        return $this->bitrate;
    }

    public function bitrateMode(): ?string
    {
        return $this->bitrateMode;
    }

    public function sampleRate(): ?int
    {
        return $this->sampleRate;
    }

    public function channels(): ?int
    {
        return $this->channels;
    }

    public function channelMode(): ?string
    {
        return $this->channelMode;
    }

    public function lossless(): bool
    {
        return $this->lossless;
    }

    public function compressionRatio(): ?float
    {
        return $this->compressionRatio;
    }
}
