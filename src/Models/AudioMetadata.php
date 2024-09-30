<?php

namespace Kiwilan\Audio\Models;

use DateTime;
use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Id3\Id3Reader;

class AudioMetadata
{
    protected function __construct(
        protected ?int $file_size = null,
        protected ?string $data_format = null,
        protected ?string $encoding = null,
        protected ?string $mime_type = null,
        protected ?float $duration_seconds = null,
        protected ?int $bitrate = null,
        protected ?string $bitrate_mode = null,
        protected ?int $sample_rate = null,
        protected ?int $channels = null,
        protected ?string $channel_mode = null,
        protected bool $is_lossless = false,
        protected ?float $compression_ratio = null,
        protected ?string $codec = null,
        protected ?string $encoder_options = null,
        protected ?string $version = null,
        protected ?int $av_data_offset = null,
        protected ?int $av_data_end = null,
        protected ?string $file_path = null,
        protected ?string $filename = null,
        protected ?DateTime $last_access_at = null,
        protected ?DateTime $created_at = null,
        protected ?DateTime $modified_at = null,
    ) {}

    public static function make(Audio $audio, Id3Reader $id3_reader): self
    {
        $path = $audio->getPath();
        $audio = $id3_reader->getAudio();
        $stat = stat($path);

        return new self(
            file_size: $id3_reader->getFileSize(),
            data_format: $audio?->dataFormat(),
            encoding: $id3_reader->getEncoding(),
            mime_type: $id3_reader->getMimeType(),
            duration_seconds: $id3_reader->getPlaytimeSeconds(),
            bitrate: intval($id3_reader->getBitrate()),
            bitrate_mode: $audio?->bitrateMode(),
            sample_rate: $audio?->sampleRate(),
            channels: $audio?->channels(),
            channel_mode: $audio?->channelMode(),
            is_lossless: $audio?->lossless() ?? false,
            compression_ratio: $audio?->compressionRatio(),
            codec: $audio?->codec(),
            encoder_options: $audio?->encoderOptions(),
            version: $id3_reader->getVersion(),
            av_data_offset: $id3_reader->getAvDataOffset(),
            av_data_end: $id3_reader->getAvDataEnd(),
            file_path: $id3_reader->getFilePath(),
            filename: $id3_reader->getFilename(),
            last_access_at: $stat['atime'] ? new DateTime('@'.$stat['atime']) : null,
            created_at: $stat['ctime'] ? new DateTime('@'.$stat['ctime']) : null,
            modified_at: $stat['mtime'] ? new DateTime('@'.$stat['mtime']) : null,
        );
    }

    /**
     * Get size of the audio file in bytes, like `180664`
     */
    public function getFileSize(): ?int
    {
        return $this->file_size;
    }

    /**
     * Get size of the audio file in human readable format, like `175.99 KB`
     */
    public function getSizeHuman(int $decimals = 2): ?string
    {
        $file_size = (string) $this->file_size;
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $factor = floor((strlen($file_size) - 1) / 3);

        return sprintf("%.{$decimals}f", $file_size / pow(1024, $factor)).' '.$size[$factor];
    }

    /**
     * Get data format of the audio file, like `mp3`, `wav`, `etc`
     */
    public function getDataFormat(): ?string
    {
        return $this->data_format;
    }

    /**
     * Get encoding of the audio file, like `UTF-8`, `ISO-8859-1`, `etc`
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    /**
     * Get mime type of the audio file, like `audio/x-matroska`, `audio/mpeg`, `etc`
     */
    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    /**
     * Get duration of the audio file in seconds, like `11.05`
     */
    public function getDurationSeconds(?int $decimals = null): ?float
    {
        if ($decimals !== null) {
            return round($this->duration_seconds, $decimals);
        }

        return $this->duration_seconds;
    }

    /**
     * Get bitrate of the audio file in bits per second, like `128000`
     */
    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    /**
     * Get bitrate mode of the audio file, like `cbr`, `vbr`, `etc`
     */
    public function getBitrateMode(): ?string
    {
        return $this->bitrate_mode;
    }

    /**
     * Get sample rate of the audio file in hertz, like `44100`
     */
    public function getSampleRate(): ?int
    {
        return $this->sample_rate;
    }

    /**
     * Get channels of the audio file, like `2`
     */
    public function getChannels(): ?int
    {
        return $this->channels;
    }

    /**
     * Get channel mode of the audio file, like `joint stereo`, `stereo`, `etc`
     */
    public function getChannelMode(): ?string
    {
        return $this->channel_mode;
    }

    /**
     * Get lossless status of the audio file, like `false`
     */
    public function isLossless(): bool
    {
        return $this->is_lossless;
    }

    /**
     * Get compression ratio of the audio file, like `0.1`
     */
    public function getCompressionRatio(?int $decimals = null): ?float
    {
        if ($decimals !== null) {
            return round($this->compression_ratio, $decimals);
        }

        return $this->compression_ratio;
    }

    /**
     * Get codec of the audio file, like `LAME`
     */
    public function getCodec(): ?string
    {
        return $this->codec;
    }

    /**
     * Get encoder options of the audio file, like `CBR`, `VBR`, `etc`
     */
    public function getEncoderOptions(): ?string
    {
        return $this->encoder_options;
    }

    /**
     * Get version of `JamesHeinrich/getID3`, like `1.9.23-202310190849`
     *
     * @docs https://github.com/JamesHeinrich/getID3
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Get audio/video data offset of the audio file, like `25808`
     */
    public function getAvDataOffset(): ?int
    {
        return $this->av_data_offset;
    }

    /**
     * Get audio/video data end of the audio file, like `1214046`
     */
    public function getAvDataEnd(): ?int
    {
        return $this->av_data_end;
    }

    /**
     * Get path of audio file directory, like `/path/to`
     */
    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    /**
     * Get filename of the audio file, like `audio.mp3`
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Get last access time of the audio file, like `2021-09-01 00:00:00`
     */
    public function getLastAccessAt(): ?DateTime
    {
        return $this->last_access_at;
    }

    /**
     * Get created time of the audio file, like `2021-09-01 00:00:00`
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    /**
     * Get modified time of the audio file, like `2021-09-01 00:00:00`
     */
    public function getModifiedAt(): ?DateTime
    {
        return $this->modified_at;
    }
}
