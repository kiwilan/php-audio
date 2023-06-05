<?php

namespace Kiwilan\Audio\Models;

class AudioCore
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $genre = null,
        protected ?int $year = null,
        protected ?string $trackNumber = null,
        protected ?string $comment = null,
        protected ?string $albumArtist = null,
        protected ?string $composer = null,
        protected ?string $discNumber = null,
        protected ?bool $isCompilation = false,
        protected ?string $creationDate = null,
        protected ?string $copyright = null,
        protected ?string $encodingBy = null,
        protected ?string $encoding = null,
        protected ?string $description = null,
        protected ?string $lyrics = null,
        protected ?string $stik = null,
        protected bool $hasCover = false,
        protected ?AudioCoreCover $cover = null,
    ) {
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
        if ($this->isCompilation === null) {
            return false;
        }

        return $this->isCompilation;
    }

    public function creationDate(): ?string
    {
        return $this->creationDate;
    }

    public function copyright(): ?string
    {
        return $this->copyright;
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

    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    public function cover(): ?AudioCoreCover
    {
        return $this->cover;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function setAlbum(?string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function setTrackNumber(?string $trackNumber): self
    {
        $this->trackNumber = $trackNumber;

        return $this;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function setAlbumArtist(?string $albumArtist): self
    {
        $this->albumArtist = $albumArtist;

        return $this;
    }

    public function setComposer(?string $composer): self
    {
        $this->composer = $composer;

        return $this;
    }

    public function setDiscNumber(?string $discNumber): self
    {
        $this->discNumber = $discNumber;

        return $this;
    }

    public function setIsCompilation(bool $isCompilation): self
    {
        $this->isCompilation = $isCompilation;

        return $this;
    }

    public function setCreationDate(?string $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function setEncodingBy(?string $encodingBy): self
    {
        $this->encodingBy = $encodingBy;

        return $this;
    }

    public function setEncoding(?string $encoding): self
    {
        $this->encoding = $encoding;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    public function setStik(?string $stik): self
    {
        $this->stik = $stik;

        return $this;
    }

    public function setHasCover(bool $hasCover): self
    {
        $this->hasCover = $hasCover;

        return $this;
    }

    public function setCover(string $pathOrData): self
    {
        $this->cover = AudioCoreCover::make($pathOrData);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'genre' => $this->genre,
            'year' => $this->year,
            'trackNumber' => $this->trackNumber,
            'comment' => $this->comment,
            'albumArtist' => $this->albumArtist,
            'composer' => $this->composer,
            'discNumber' => $this->discNumber,
            'isCompilation' => $this->isCompilation,
            'creationDate' => $this->creationDate,
            'encodingBy' => $this->encodingBy,
            'encoding' => $this->encoding,
            'description' => $this->description,
            'lyrics' => $this->lyrics,
            'stik' => $this->stik,
            'hasCover' => $this->hasCover,
            'cover' => $this->cover?->toArray(),
        ];
    }
}

class AudioCoreCover
{
    public function __construct(
        protected ?string $data = null,
        protected ?string $picturetypeid = null,
        protected ?string $description = null,
        protected ?string $mime = null,
    ) {
    }

    public static function make(string $pathOrData): self
    {
        $self = new self();

        if (file_exists($pathOrData)) {
            $image = getimagesize($pathOrData);
            $self->data = base64_encode(file_get_contents($pathOrData));
            $self->picturetypeid = $image[2];
            $self->description = 'cover';
            $self->mime = $image['mime'];

            return $self;
        }

        $image = getimagesizefromstring($pathOrData);
        $self->data = base64_encode($pathOrData);
        $self->picturetypeid = $image[2];
        $self->mime = $image['mime'];
        $self->description = 'cover';

        return $self;
    }

    public function data(): ?string
    {
        return $this->data;
    }

    public function picturetypeid(): ?string
    {
        return $this->picturetypeid;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function mime(): ?string
    {
        return $this->mime;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'picturetypeid' => $this->picturetypeid,
            'description' => $this->description,
            'mime' => $this->mime,
        ];
    }
}
