<?php

namespace Kiwilan\Audio\Id3;

use getID3;
use Kiwilan\Audio\Id3\Reader\Id3Audio;
use Kiwilan\Audio\Id3\Reader\Id3AudioQuicktime;
use Kiwilan\Audio\Id3\Reader\Id3AudioTag;
use Kiwilan\Audio\Id3\Reader\Id3Comments;
use Kiwilan\Audio\Id3\Reader\Id3Video;

class Id3Reader
{
    protected function __construct(
        protected getID3 $instance,
        protected bool $is_writable = false,
        protected ?string $version = null,
        protected ?int $file_size = null,
        protected ?string $file_path = null,
        protected ?string $filename = null,
        protected ?string $filename_path = null,
        protected ?int $av_data_offset = null,
        protected ?int $av_data_end = null,
        protected ?string $file_format = null,
        protected ?Id3Audio $audio = null,
        protected ?Id3Video $video = null,
        protected ?Id3AudioTag $tags = null,
        protected ?array $warning = null,
        protected ?Id3Comments $comments = null,
        protected ?Id3AudioQuicktime $quicktime = null,
        protected ?string $encoding = null,
        protected ?string $mime_type = null,
        protected ?array $mpeg = null,
        protected ?float $playtime_seconds = null,
        protected ?float $bitrate = null,
        protected ?string $playtime_string = null,
        protected array $raw = [],
    ) {}

    public static function make(string $path): self
    {
        $self = new self(new getID3);

        $self->raw = $self->instance->analyze($path);
        $self->is_writable = $self->instance->is_writable($path);
        $metadata = $self->raw;

        $audio = Id3Audio::make($metadata['audio'] ?? null);
        $video = Id3Video::make($metadata['video'] ?? null);
        $tags = Id3AudioTag::make($metadata['tags'] ?? null);
        $comments = Id3Comments::make($metadata['comments'] ?? null);
        $quicktime = Id3AudioQuicktime::make($self->raw['quicktime'] ?? null);
        $warning = $metadata['warning'] ?? null;

        $bitrate = $metadata['bitrate'] ?? null;
        if ($bitrate) {
            $bitrate = intval($bitrate);
        }

        $self->version = $metadata['GETID3_VERSION'] ?? null;
        $self->file_size = $metadata['filesize'] ?? null;
        $self->file_path = $metadata['filepath'] ?? null;
        $self->filename = $metadata['filename'] ?? null;
        $self->filename_path = $metadata['filenamepath'] ?? null;
        $self->av_data_offset = $metadata['avdataoffset'] ?? null;
        $self->av_data_end = $metadata['avdataend'] ?? null;
        $self->file_format = $metadata['fileformat'] ?? null;
        $self->audio = $audio;
        $self->video = $video;
        $self->tags = $tags;
        $self->quicktime = $quicktime;
        $self->comments = $comments;
        $self->warning = $warning;
        $self->encoding = $metadata['encoding'] ?? null;
        $self->mime_type = $metadata['mime_type'] ?? null;
        $self->mpeg = $metadata['mpeg'] ?? null;
        $self->playtime_seconds = $metadata['playtime_seconds'] ?? null;
        $self->bitrate = $bitrate;
        $self->playtime_string = $metadata['playtime_string'] ?? null;

        return $self;
    }

    public function getInstance(): getID3
    {
        return $this->instance;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getFileSize(): ?int
    {
        return $this->file_size;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function getFilenamePath(): ?string
    {
        return $this->filename_path;
    }

    public function getAvDataOffset(): ?int
    {
        return $this->av_data_offset;
    }

    public function getAvDataEnd(): ?int
    {
        return $this->av_data_end;
    }

    public function getFileFormat(): ?string
    {
        return $this->file_format;
    }

    public function getAudio(): ?Id3Audio
    {
        return $this->audio;
    }

    public function getTags(): ?Id3AudioTag
    {
        return $this->tags;
    }

    public function getComments(): ?Id3Comments
    {
        return $this->comments;
    }

    public function getVideo(): ?Id3Video
    {
        return $this->video;
    }

    public function getQuicktime(): ?Id3AudioQuicktime
    {
        return $this->quicktime;
    }

    public function getWarning(): ?array
    {
        return $this->warning;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function getMpeg(): mixed
    {
        return $this->mpeg;
    }

    public function getPlaytimeSeconds(): ?float
    {
        return $this->playtime_seconds;
    }

    public function getBitrate(): ?float
    {
        return $this->bitrate;
    }

    public function getPlaytimeString(): ?string
    {
        return $this->playtime_string;
    }

    public function isWritable(): bool
    {
        return $this->is_writable;
    }

    public function getRaw(): array
    {
        return $this->raw;
    }

    public function toTags(?string $audioFormat = null): array
    {
        $rawTags = $this->raw['tags_html'] ?? [];

        if (count($rawTags) === 0) {
            return [];
        }

        $tagsItems = [];
        if ($audioFormat) {
            $tagsItems = $rawTags[$audioFormat] ?? [];
        } else {
            if (count($rawTags) > 1) {
                $entries = [];
                foreach ($rawTags as $key => $keyTags) {
                    $entries[$key] = count($keyTags);
                }
                $maxKey = array_search(max($entries), $entries);
                $tagsItems = $rawTags[$maxKey] ?? [];
            } else {
                $tagsItems = reset($rawTags);
            }
        }

        return Id3Reader::cleanTags($tagsItems);
    }

    public static function cleanTags(?array $tagsItems): array
    {
        if (! $tagsItems) {
            return [];
        }

        $temp = [];
        foreach ($tagsItems as $k => $v) {
            $temp[$k] = $v[0] ?? null;
        }

        $items = [];
        foreach ($temp as $k => $v) {
            $k = strtolower($k);
            $k = str_replace(' ', '_', $k);
            $items[$k] = $v;
        }

        return $items;
    }

    public function toAudioFormats(): array
    {
        return $this->raw['tags_html'] ?? [];
    }

    public function toArray(): array
    {
        $raw = $this->raw;
        $raw['id3v2']['APIC'] = null;
        $raw['ape']['items']['cover art (front)'] = null;
        $raw['comments'] = null;

        return $raw;
    }
}
