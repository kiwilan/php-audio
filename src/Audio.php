<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;
use Kiwilan\Audio\Models\FileStat;

class Audio
{
    protected ?string $title = null;

    protected ?string $artist = null;

    protected ?string $album = null;

    protected ?string $genre = null;

    protected ?string $year = null;

    protected ?string $trackNumber = null;

    protected ?string $comment = null;

    protected ?string $albumArtist = null;

    protected ?string $composer = null;

    protected ?string $discNumber = null;

    protected bool $isCompilation = false;

    protected ?string $creationDate = null;

    protected ?string $copyright = null;

    protected ?string $encodedBy = null;

    protected ?string $encodingTool = null;

    protected ?string $description = null;

    protected ?string $descriptionLong = null;

    protected ?string $lyrics = null;

    protected ?string $stik = null;

    protected ?AudioMetadata $metadata = null;

    protected ?AudioCover $cover = null;

    protected function __construct(
        protected string $path,
        protected string $extension,
        protected Id3 $id3,
        protected FileStat $stat,
    ) {
    }

    public static function read(string $path): self
    {
        $self = new self(
            path: $path,
            extension: pathinfo($path, PATHINFO_EXTENSION),
            id3: Id3::make($path),
            stat: FileStat::make($path)
        );
        $self->metadata = AudioMetadata::make($self->id3->item());
        $self->parse();

        return $self;
    }

    public function id3(): Id3
    {
        return $this->id3;
    }

    public function stat(): ?FileStat
    {
        return $this->stat;
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

    public function year(): ?string
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

    public function encodedBy(): ?string
    {
        return $this->encodedBy;
    }

    public function encodingTool(): ?string
    {
        return $this->encodingTool;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function descriptionLong(): ?string
    {
        return $this->descriptionLong;
    }

    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    public function stik(): ?string
    {
        return $this->stik;
    }

    public function metadata(): ?AudioMetadata
    {
        return $this->metadata;
    }

    public function cover(): ?AudioCover
    {
        return $this->cover;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function extension(): string
    {
        return $this->extension;
    }

    private function parse(): void
    {
        $item = $this->id3->item();

        $tags = $item->tags();
        if (! $tags) {
            return;
        }
        $v1 = $tags->id3v1();
        $v2 = $tags->id3v2();
        $quicktime = $tags->quicktime();

        if ($v1 || $v2) {
            $this->title = $v2?->title() ?? $v1?->title();
            $this->artist = $v2?->artist() ?? $v1?->artist();
            $this->album = $v2?->album() ?? $v1?->album();
            $this->genre = $v2?->genre() ?? $v1?->genre();
            $this->year = $v2?->year() ?? $v1?->year();
            $this->trackNumber = $v2?->track_number() ?? $v1?->track_number();
            $this->comment = $v2?->comment() ?? $v1?->comment();
            $this->albumArtist = $v2?->band() ?? null;
            $this->composer = $v2?->composer() ?? null;
            $this->discNumber = $v2?->part_of_a_set() ?? null;
            $this->isCompilation = $v2?->part_of_a_compilation() ?? false;
        }

        if ($quicktime) {
            $this->title = $quicktime->title();
            $this->artist = $quicktime->artist();
            $this->album = $quicktime->album();
            $this->genre = $quicktime->genre();
            $this->trackNumber = $quicktime->track_number();
            $this->comment = $quicktime->comment();
            $this->albumArtist = $quicktime->album_artist();
            $this->creationDate = $quicktime->creation_date();
            $this->encodedBy = $quicktime->encoded_by();
            $this->encodingTool = $quicktime->encoding_tool();
            $this->description = $quicktime->description();
            $this->descriptionLong = $quicktime->description_long();
            $this->lyrics = $quicktime->lyrics();
            $this->stik = $quicktime->stik();
        }

        $this->metadata = AudioMetadata::make($item);
        $this->cover = AudioCover::make($item->comments());
    }
}
