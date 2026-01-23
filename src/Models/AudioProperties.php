<?php

namespace Kiwilan\Audio\Models;

/**
 * Represents audio technical from stream,
 * like `duration`, `codec`, `channels`, `bitrate`
 */
class AudioProperties
{
    public function __construct(
        protected ?float $duration = null, // 10.0
        protected ?int $bit_rate = null, // 128000
        protected ?string $codec = null, // mp3
        protected ?int $sample_rate = null, // 48000
        protected ?int $channels = null, // 2
        protected ?string $channel_layout = null, // stereo
        protected ?string $format_type = null, // mov,mp4,m4a,3gp,3g2,mj2
        protected ?string $format_label = null, // QuickTime / MOV
        protected ?float $start_time = null, // 0.000000
        protected ?array $streams = [],
        protected ?array $raw = [],
    ) {}

    public static function handle(array $data): self
    {
        $self = new self;
        if (! $data) {
            return $self;
        }

        $audio = $self->getTypeStream($data, 'audio');
        // $video = $self->getTypeStream($data, 'video');

        if (! $audio) {
            return $self;
        }

        $self->raw = $data;
        $self->streams = $self->ek($data, 'streams');

        $self->duration = (float) $self->ek($audio, 'duration');
        $self->bit_rate = (int) $self->ek($audio, 'bit_rate');
        $self->codec = $self->ek($audio, 'codec_name');
        $self->sample_rate = (int) $self->ek($audio, 'sample_rate');
        $self->channels = (int) $self->ek($audio, 'channels');
        $self->channel_layout = $self->ek($audio, 'channel_layout');

        $format = $self->ek($data, 'format');
        if ($format) {
            $self->format_type = $self->ek($format, 'format_name');
            $self->format_label = $self->ek($format, 'format_long_name');
            $self->start_time = $self->ek($format, 'start_time');
        }

        return $self;

    }

    private function ek(array $data, string $key): mixed
    {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        }

        return null;
    }

    private function getTypeStream(array $data, string $type)
    {
        $audio_index = null;
        $streams = $this->ek($data, 'streams');

        if (! $streams) {
            return null;
        }

        foreach ($streams as $key => $stream) {
            $codec_type = $stream['codec_type'] ?? null;
            if ($codec_type === $type) {
                $audio_index = $key;
            }
        }

        return $streams[$audio_index] ?? null;
    }

    /**
     * Get duration of the audio file in seconds, limited to 2 decimals, like `180.66`
     */
    public function getDuration(): ?float
    {
        return $this->duration;
    }

    /**
     * Get duration of the audio file in human readable format, like `00:03:00`
     */
    public function getDurationHuman(): ?string
    {
        return gmdate('H:i:s', intval($this->duration));
    }

    /**
     * Get bitrate, like `128000`.
     */
    public function getBitrate(): ?int
    {
        return $this->bit_rate;
    }

    /**
     * Get codec, like `mp3`.
     */
    public function getCodec(): ?string
    {
        return $this->codec;
    }

    /**
     * Get sample rate, like `48000`.
     */
    public function getSampleRate(): ?int
    {
        return $this->sample_rate;
    }

    /**
     * Get channels, like `2`.
     */
    public function getChannels(): ?int
    {
        return $this->channels;
    }

    /**
     * Get channel layout, like `stereo`.
     */
    public function getChannelLayout(): ?string
    {
        return $this->channel_layout;
    }

    /**
     * Get audio format type, like `mov,mp4,m4a,3gp,3g2,mj2`.
     */
    public function getFormatType(): ?string
    {
        return $this->format_type;
    }

    /**
     * Get audio format label, like `QuickTime / MOV`.
     */
    public function getFormatLabel(): ?string
    {
        return $this->format_label;
    }

    /**
     * Get audio start time, like `0.000000`
     */
    public function getStartTime(): ?float
    {
        return $this->start_time;
    }

    /**
     * Get audio file streams.
     */
    public function getStreams(): ?array
    {
        return $this->streams;
    }

    public function getRaw(): ?array
    {
        return $this->raw;
    }
}
