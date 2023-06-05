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

    public function update(): Id3Writer
    {
        return $this->writer->write();
    }

    public function stat(): ?FileStat
    {
        return $this->stat;
    }

    public function reader(): Id3Reader
    {
        return $this->reader;
    }

    public function writer(): ?Id3Writer
    {
        return $this->writer;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function year(): ?int
    {
        return $this->year;
    }

    public function trackNumber(): ?string
    {
        return $this->trackNumber;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function albumArtist(): ?string
    {
        return $this->albumArtist;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function discNumber(): ?string
    {
        return $this->discNumber;
    }

    public function isCompilation(): bool
    {
        return $this->isCompilation;
    }

    public function creationDate(): ?string
    {
        return $this->creationDate;
    }

    public function encodingBy(): ?string
    {
        return $this->encodingBy;
    }

    public function encoding(): ?string
    {
        return $this->encoding;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    public function stik(): ?string
    {
        return $this->stik;
    }

    public function duration(): ?float
    {
        return $this->duration;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function extras(): array
    {
        return $this->extras;
    }

    public function audio(): ?AudioMetadata
    {
        return $this->audio;
    }

    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    public function cover(): ?AudioCover
    {
        return $this->cover;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function format(): AudioFormatEnum
    {
        return $this->format;
    }

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
        $this->description = $core->description();
        $this->lyrics = $core->lyrics();
        $this->stik = $core->stik();

        return $this;
    }
}
