<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;
use Kiwilan\Audio\Models\AudioCore;
use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;
use Kiwilan\Audio\Models\FileStat;
use Kiwilan\Audio\Models\Id3Reader;
use Kiwilan\Audio\Models\Id3Writer;

class Audio
{
    protected ?string $title = null;

    protected ?string $artist = null;

    protected ?string $album = null;

    protected ?string $genre = null;

    protected ?int $year = null;

    protected ?string $trackNumber = null;

    protected ?string $comment = null;

    protected ?string $albumArtist = null;

    protected ?string $composer = null;

    protected ?string $discNumber = null;

    protected bool $isCompilation = false;

    protected ?string $creationDate = null;

    protected ?string $copyright = null;

    protected ?string $encodingBy = null;

    protected ?string $encoding = null;

    protected ?string $description = null;

    protected ?string $lyrics = null;

    protected ?string $stik = null;

    protected ?float $duration = null;

    protected array $extras = [];

    protected ?AudioMetadata $audio = null;

    protected bool $hasCover = false;

    protected ?AudioCover $cover = null;

    protected bool $isValid = false;

    protected ?AudioTypeEnum $type = null;

    protected function __construct(
        protected string $path,
        protected AudioFormatEnum $format,
        protected FileStat $stat,
        protected Id3Reader $reader,
        protected ?Id3Writer $writer = null,
    ) {
    }

    public static function get(string $path): self
    {
        $fileExists = file_exists($path);
        if (! $fileExists) {
            throw new \Exception("File not found: {$path}");
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        $format = AudioFormatEnum::tryFrom($extension);

        $self = new self(
            path: $path,
            format: $format ? $format : AudioFormatEnum::unknown,
            stat: FileStat::make($path),
            reader: Id3Reader::make($path),
        );
        if ($self->reader->is_writable()) {
            $self->writer = Id3Writer::make($self);
        }
        $self->audio = AudioMetadata::make($self);
        $self->parse();

        return $self;
    }

    /**
     * Update metadata of audio file.
     */
    public function update(): Id3Writer
    {
        return $this->writer->write();
    }

    /**
     * Get the value of `stat` method.
     */
    public function stat(): ?FileStat
    {
        return $this->stat;
    }

    /**
     * `Id3Reader` with metadata.
     */
    public function reader(): Id3Reader
    {
        return $this->reader;
    }

    /**
     * `Id3Writer` to update metadata.
     */
    public function writer(): ?Id3Writer
    {
        return $this->writer;
    }

    /**
     * Get `title` metadata.
     */
    public function title(): ?string
    {
        return $this->title;
    }

    /**
     * Get `artist` metadata.
     */
    public function artist(): ?string
    {
        return $this->artist;
    }

    /**
     * Get `album` metadata.
     */
    public function album(): ?string
    {
        return $this->album;
    }

    /**
     * Get `genre` metadata.
     */
    public function genre(): ?string
    {
        return $this->genre;
    }

    /**
     * Get `year` metadata.
     */
    public function year(): ?int
    {
        return $this->year;
    }

    /**
     * Get `trackNumber` metadata.
     */
    public function trackNumber(): ?string
    {
        return $this->trackNumber;
    }

    /**
     * Get `comment` metadata.
     */
    public function comment(): ?string
    {
        return $this->comment;
    }

    /**
     * Get `albumArtist` metadata.
     */
    public function albumArtist(): ?string
    {
        return $this->albumArtist;
    }

    /**
     * Get `composer` metadata.
     */
    public function composer(): ?string
    {
        return $this->composer;
    }

    /**
     * Get `discNumber` metadata.
     */
    public function discNumber(): ?string
    {
        return $this->discNumber;
    }

    /**
     * Know if audio file is a compilation.
     */
    public function isCompilation(): bool
    {
        return $this->isCompilation;
    }

    /**
     * Get `creationDate` metadata for audiobook.
     */
    public function creationDate(): ?string
    {
        return $this->creationDate;
    }

    /**
     * Get `encodingBy` metadata for audiobook.
     */
    public function encodingBy(): ?string
    {
        return $this->encodingBy;
    }

    /**
     * Get `encoding` metadata for audiobook.
     */
    public function encoding(): ?string
    {
        return $this->encoding;
    }

    /**
     * Get `encoding` metadata for audiobook.
     */
    public function copyright(): ?string
    {
        return $this->copyright;
    }

    /**
     * Get `description` metadata for audiobook.
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Get `lyrics` metadata for audiobook.
     */
    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    /**
     * Get `stik` metadata for audiobook.
     */
    public function stik(): ?string
    {
        return $this->stik;
    }

    /**
     * Get `duration` in seconds.
     */
    public function duration(): ?float
    {
        return $this->duration;
    }

    /**
     * Know if audio file is valid.
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Get `extras` with raw metadata.
     */
    public function extras(): array
    {
        return $this->extras;
    }

    /**
     * Get `audio` metadata with some audio information.
     */
    public function audio(): ?AudioMetadata
    {
        return $this->audio;
    }

    /**
     * Know if audio file has cover.
     */
    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    /**
     * Get `cover` metadata with some cover information.
     */
    public function cover(): ?AudioCover
    {
        return $this->cover;
    }

    /**
     * Get `path` of audio file.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Get `format` of audio file.
     */
    public function format(): AudioFormatEnum
    {
        return $this->format;
    }

    /**
     * Get `type` of audio file.
     */
    public function type(): ?AudioTypeEnum
    {
        return $this->type;
    }

    private function parse(): self
    {
        $raw = $this->reader()->raw();
        $reader = $this->reader();

        $this->type = match ($this->format) {
            AudioFormatEnum::aac => null,
            AudioFormatEnum::aif => AudioTypeEnum::id3,
            AudioFormatEnum::aifc => AudioTypeEnum::id3,
            AudioFormatEnum::aiff => AudioTypeEnum::id3,
            AudioFormatEnum::flac => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::m4a => AudioTypeEnum::quicktime,
            AudioFormatEnum::m4b => AudioTypeEnum::quicktime,
            AudioFormatEnum::m4v => AudioTypeEnum::quicktime,
            AudioFormatEnum::mka => AudioTypeEnum::matroska,
            AudioFormatEnum::mkv => AudioTypeEnum::matroska,
            AudioFormatEnum::mp3 => AudioTypeEnum::id3,
            AudioFormatEnum::mp4 => AudioTypeEnum::quicktime,
            AudioFormatEnum::ogg => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::opus => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::spx => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::tta => AudioTypeEnum::ape,
            AudioFormatEnum::wav => AudioTypeEnum::id3,
            AudioFormatEnum::webm => AudioTypeEnum::matroska,
            AudioFormatEnum::wma => AudioTypeEnum::asf,
            AudioFormatEnum::wv => AudioTypeEnum::ape,
            default => null,
        };

        $tags = $reader->tags();
        if (! $tags) {
            return $this;
        }

        $core = null;
        if ($this->type === AudioTypeEnum::id3) {
            $core = AudioConverter::fromId3($tags->id3v1(), $tags->id3v2());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::quicktime) {
            $core = AudioConverter::fromQuicktime($tags->quicktime());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::vorbiscomment) {
            $core = AudioConverter::fromVorbisComment($tags->vorbiscomment());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::asf) {
            $core = AudioConverter::fromAsf($tags->asf());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::matroska) {
            $core = AudioConverter::fromMatroska($tags->matroska());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::ape) {
            $core = AudioConverter::fromApe($tags->ape());
            $this->isValid = true;
        }

        $this->coreToProperties($core);
        $this->extras = $raw['tags'] ?? [];

        $this->audio = AudioMetadata::make($this);
        $this->cover = AudioCover::make($reader->comments());

        if ($this->cover?->content()) {
            $this->hasCover = true;
        }

        $this->duration = number_format((float) $this->audio->durationSeconds(), 2, '.', '');

        return $this;
    }

    private function coreToProperties(?AudioCore $core): self
    {
        if (! $core) {
            return $this;
        }

        $this->title = $core->title();
        $this->artist = $core->artist();
        $this->album = $core->album();
        $this->genre = $core->genre();
        $this->year = $core->year();
        $this->trackNumber = $core->trackNumber();
        $this->comment = $core->comment();
        $this->albumArtist = $core->albumArtist();
        $this->composer = $core->composer();
        $this->discNumber = $core->discNumber();
        $this->isCompilation = $core->isCompilation();
        $this->creationDate = $core->creationDate();
        $this->encodingBy = $core->encodingBy();
        $this->encoding = $core->encoding();
        $this->copyright = $core->copyright();
        $this->description = $core->description();
        $this->lyrics = $core->lyrics();
        $this->stik = $core->stik();

        return $this;
    }
}
