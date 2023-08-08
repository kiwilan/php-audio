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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function getTrackNumber(): ?string
    {
        return $this->trackNumber;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getAlbumArtist(): ?string
    {
        return $this->albumArtist;
    }

    public function getComposer(): ?string
    {
        return $this->composer;
    }

    public function getDiscNumber(): ?string
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

    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function getEncodingBy(): ?string
    {
        return $this->encodingBy;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function getStik(): ?string
    {
        return $this->stik;
    }

    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    public function getCover(): ?AudioCoreCover
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

    public static function toId3v2(AudioCore $core): Id3AudioTagV2
    {
        return new Id3AudioTagV2(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            band: $core->getAlbumArtist(),
            comment: $core->getComment(),
            composer: $core->getComposer(),
            part_of_a_set: $core->getDiscNumber(),
            genre: $core->getGenre(),
            part_of_a_compilation: $core->isCompilation() ? '1' : '0',
            title: $core->getTitle(),
            track_number: $core->getTrackNumber(),
            year: $core->getYear(),
        );
    }

    public static function toId3v1(AudioCore $core): Id3AudioTagV1
    {
        return new Id3AudioTagV1(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            comment: $core->getComment(),
            genre: $core->getGenre(),
            title: $core->getTitle(),
            track_number: $core->getTrackNumber(),
            year: $core->getYear(),
        );
    }

    public static function toVorbisComment(AudioCore $core): Id3TagVorbisComment
    {
        return new Id3TagVorbisComment(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            albumartist: $core->getAlbumArtist(),
            comment: $core->getComment(),
            composer: $core->getComposer(),
            compilation: $core->isCompilation() ? '1' : '0',
            discnumber: $core->getDiscNumber(),
            genre: $core->getGenre(),
            title: $core->getTitle(),
            tracknumber: $core->getTrackNumber(),
            date: $core->getYear(),
            encoder: $core->getEncoding(),
            description: $core->getDescription(),
        );
    }

    public static function toQuicktime(AudioCore $core): Id3TagQuicktime
    {
        return new Id3TagQuicktime(
            title: $core->getTitle(),
            track_number: $core->getTrackNumber(),
            disc_number: $core->getDiscNumber(),
            compilation: $core->isCompilation() ? '1' : '0',
            album: $core->getAlbum(),
            genre: $core->getGenre(),
            composer: $core->getComposer(),
            creation_date: $core->getCreationDate(),
            copyright: $core->getCopyright(),
            artist: $core->getArtist(),
            album_artist: $core->getAlbumArtist(),
            encoded_by: $core->getEncoding(),
            encoding_tool: $core->getEncoding(),
            description: $core->getDescription(),
            description_long: $core->getDescription(),
            lyrics: $core->getLyrics(),
            comment: $core->getComment(),
            stik: $core->getStik(),
        );
    }

    public static function toMatroska(AudioCore $core): Id3TagMatroska
    {
        return new Id3TagMatroska(
            title: $core->getTitle(),
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            album_artist: $core->getAlbumArtist(),
            comment: $core->getComment(),
            composer: $core->getComposer(),
            disc: $core->getDiscNumber(),
            compilation: $core->isCompilation() ? '1' : '0',
            genre: $core->getGenre(),
            part_number: $core->getTrackNumber(),
            date: $core->getYear(),
            encoder: $core->getEncoding(),
        );
    }

    public static function toApe(AudioCore $core): Id3TagApe
    {
        return new Id3TagApe(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            album_artist: $core->getAlbumArtist(),
            comment: $core->getComment(),
            composer: $core->getComposer(),
            disc: $core->getDiscNumber(),
            compilation: $core->isCompilation() ? '1' : '0',
            genre: $core->getGenre(),
            title: $core->getTitle(),
            track: $core->getTrackNumber(),
            date: $core->getYear(),
            encoder: $core->getEncoding(),
        );
    }

    public static function toAsf(AudioCore $core): Id3TagAsf
    {
        return new Id3TagAsf(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            albumartist: $core->getAlbumArtist(),
            composer: $core->getComposer(),
            partofset: $core->getDiscNumber(),
            genre: $core->getGenre(),
            track_number: $core->getTrackNumber(),
            year: $core->getYear(),
            encodingsettings: $core->getEncoding(),
        );
    }

    public static function fromId3(?Id3AudioTagV1 $v1, Id3AudioTagV2 $v2): AudioCore
    {
        return new AudioCore(
            album: $v2->album() ?? $v1->album(),
            artist: $v2->artist() ?? $v1->artist(),
            albumArtist: $v2->band() ?? null,
            comment: $v2->comment() ?? $v1->comment(),
            composer: $v2->composer() ?? null,
            discNumber: $v2->part_of_a_set() ?? null,
            genre: $v2->genre() ?? $v1->genre(),
            isCompilation: $v2->part_of_a_compilation() === '1',
            title: $v2->title() ?? $v1->title(),
            trackNumber: $v2->track_number() ?? $v1->track_number(),
            year: $v2->year() ?? $v1->year(),
        );
    }

    public static function fromId3v2(Id3AudioTagV2 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->band(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->part_of_a_set(),
            genre: $tag->genre(),
            isCompilation: $tag->part_of_a_compilation() === '1',
            title: $tag->title(),
            trackNumber: $tag->track_number(),
            year: $tag->year(),
        );
    }

    public static function fromId3v1(Id3AudioTagV1 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            comment: $tag->comment(),
            genre: $tag->genre(),
            title: $tag->title(),
            trackNumber: $tag->track_number(),
            year: $tag->year(),
        );
    }

    public static function fromQuicktime(Id3TagQuicktime $tag): AudioCore
    {

        $creation_date = $tag->creation_date();
        $description = $tag->description();
        $description_long = $tag->description_long();

        if ($description_long && $description && strlen($description_long) > strlen($description)) {
            $description = $description_long;
        }

        $core = new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            genre: $tag->genre(),
            trackNumber: $tag->track_number(),
            discNumber: $tag->disc_number(),
            composer: $tag->composer(),
            isCompilation: $tag->compilation() === '1',
            comment: $tag->comment(),
            albumArtist: $tag->album_artist(),
            encodingBy: $tag->encoded_by(),
            encoding: $tag->encoding_tool(),
        );

        if ($creation_date) {
            if (strlen($creation_date) === 4) {
                $core->setYear((int) $creation_date);
            } else {
                $creation_date = date_create_from_format('Y-m-d\TH:i:s\Z', $creation_date);
                $core->setCreationDate($creation_date?->format('Y-m-d\TH:i:s\Z'));
                $core->setYear((int) $creation_date?->format('Y'));
            }
        }

        $core->setCopyright($tag->copyright());
        $core->setDescription($description);
        $core->setLyrics($tag->lyrics());
        $core->setStik($tag->stik());

        return $core;
    }

    public static function fromVorbisComment(Id3TagVorbisComment $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            genre: $tag->genre(),
            trackNumber: $tag->trackNumber(),
            comment: $tag->comment(),
            albumArtist: $tag->albumartist(),
            composer: $tag->composer(),
            discNumber: $tag->discNumber(),
            isCompilation: $tag->compilation() === '1',
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
            description: $tag->description(),
        );
    }

    public static function fromAsf(Id3TagAsf $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            artist: $tag->artist(),
            album: $tag->album(),
            albumArtist: $tag->albumartist(),
            composer: $tag->composer(),
            discNumber: $tag->partofset(),
            genre: $tag->genre(),
            trackNumber: $tag->track_number(),
            year: (int) $tag->year(),
            encoding: $tag->encodingsettings(),
        );
    }

    public static function fromMatroska(Id3TagMatroska $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title(),
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->album_artist(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->disc(),
            genre: $tag->genre(),
            isCompilation: $tag->compilation(),
            trackNumber: $tag->part_number(),
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
        );
    }

    public static function fromApe(Id3TagApe $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album(),
            artist: $tag->artist(),
            albumArtist: $tag->album_artist(),
            comment: $tag->comment(),
            composer: $tag->composer(),
            discNumber: $tag->disc(),
            genre: $tag->genre(),
            isCompilation: $tag->compilation() === '1',
            title: $tag->title(),
            trackNumber: $tag->track(),
            year: (int) $tag->date(),
            encoding: $tag->encoder(),
        );
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
