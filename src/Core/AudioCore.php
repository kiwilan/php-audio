<?php

namespace Kiwilan\Audio\Core;

use Kiwilan\Audio\Id3\Tag;

class AudioCore
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $genre = null,
        protected ?int $year = null,
        protected ?string $track_number = null,
        protected ?string $comment = null,
        protected ?string $album_artist = null,
        protected ?string $composer = null,
        protected ?string $disc_number = null,
        protected ?bool $is_compilation = false,
        protected ?string $creation_date = null,
        protected ?string $copyright = null,
        protected ?string $encoding_by = null,
        protected ?string $encoding = null,
        protected ?string $description = null,
        protected ?string $synopsis = null,
        protected ?string $language = null,
        protected ?string $lyrics = null,
        protected bool $has_cover = false,
        protected ?AudioCoreCover $cover = null,
    ) {}

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
        return $this->track_number;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getAlbumArtist(): ?string
    {
        return $this->album_artist;
    }

    public function getComposer(): ?string
    {
        return $this->composer;
    }

    public function getDiscNumber(): ?string
    {
        return $this->disc_number;
    }

    public function isCompilation(): bool
    {
        if ($this->is_compilation === null) {
            return false;
        }

        return $this->is_compilation;
    }

    public function getCreationDate(): ?string
    {
        return $this->creation_date;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function getEncodingBy(): ?string
    {
        return $this->encoding_by;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    public function hasCover(): bool
    {
        return $this->has_cover;
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

    public function setTrackNumber(?string $track_number): self
    {
        $this->track_number = $track_number;

        return $this;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function setAlbumArtist(?string $album_artist): self
    {
        $this->album_artist = $album_artist;

        return $this;
    }

    public function setComposer(?string $composer): self
    {
        $this->composer = $composer;

        return $this;
    }

    public function setDiscNumber(?string $disc_number): self
    {
        $this->disc_number = $disc_number;

        return $this;
    }

    public function setIsCompilation(bool $is_compilation): self
    {
        $this->is_compilation = $is_compilation;

        return $this;
    }

    public function setCreationDate(?string $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function setEncodingBy(?string $encoding_by): self
    {
        $this->encoding_by = $encoding_by;

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

    public function setPodcastDescription(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    public function setHasCover(bool $has_cover): self
    {
        $this->has_cover = $has_cover;

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
            'track_number' => $this->track_number,
            'comment' => $this->comment,
            'album_artist' => $this->album_artist,
            'composer' => $this->composer,
            'disc_number' => $this->disc_number,
            'is_compilation' => $this->is_compilation,
            'creation_date' => $this->creation_date,
            'encoding_by' => $this->encoding_by,
            'encoding' => $this->encoding,
            'description' => $this->description,
            'synopsis' => $this->synopsis,
            'language' => $this->language,
            'lyrics' => $this->lyrics,
            'has_cover' => $this->has_cover,
            'cover' => $this->cover?->toArray(),
        ];
    }

    public static function toId3v2(AudioCore $core): Tag\Id3TagAudioV2
    {
        return new Tag\Id3TagAudioV2(
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
            year: (string) $core->getYear(),
            copyright: $core->getCopyright(),
            text: $core->getSynopsis(),
            unsynchronised_lyric: $core->getLyrics(),
            language: $core->getLanguage(),
        );
    }

    public static function toId3v1(AudioCore $core): Tag\Id3TagAudioV1
    {
        return new Tag\Id3TagAudioV1(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            comment: $core->getComment(),
            genre: $core->getGenre(),
            title: $core->getTitle(),
            track_number: $core->getTrackNumber(),
            year: (string) $core->getYear(),
        );
    }

    public static function toVorbisComment(AudioCore $core): Tag\Id3TagVorbisComment
    {
        return new Tag\Id3TagVorbisComment(
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
            date: (string) $core->getYear(),
            encoder: $core->getEncoding(),
            description: $core->getDescription(),
        );
    }

    public static function toQuicktime(AudioCore $core): Tag\Id3TagQuicktime
    {
        return new Tag\Id3TagQuicktime(
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
            description_long: $core->getSynopsis(),
            lyrics: $core->getLyrics(),
            comment: $core->getComment(),
        );
    }

    public static function toMatroska(AudioCore $core): Tag\Id3TagMatroska
    {
        return new Tag\Id3TagMatroska(
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
            date: (string) $core->getYear(),
            encoder: $core->getEncoding(),
        );
    }

    public static function toApe(AudioCore $core): Tag\Id3TagApe
    {
        return new Tag\Id3TagApe(
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
            date: (string) $core->getYear(),
            encoder: $core->getEncoding(),
        );
    }

    public static function toAsf(AudioCore $core): Tag\Id3TagAsf
    {
        return new Tag\Id3TagAsf(
            album: $core->getAlbum(),
            artist: $core->getArtist(),
            albumartist: $core->getAlbumArtist(),
            composer: $core->getComposer(),
            partofset: $core->getDiscNumber(),
            genre: $core->getGenre(),
            track_number: $core->getTrackNumber(),
            year: (string) $core->getYear(),
            encodingsettings: $core->getEncoding(),
        );
    }

    public static function fromId3(?Tag\Id3TagAudioV1 $v1, ?Tag\Id3TagAudioV2 $v2): AudioCore
    {
        if (! $v1) {
            $v1 = new Tag\Id3TagAudioV1;
        }

        if (! $v2) {
            $v2 = new Tag\Id3TagAudioV2;
        }

        return new AudioCore(
            album: $v2->album ?? $v1->album,
            artist: $v2->artist ?? $v1->artist,
            album_artist: $v2->band ?? null,
            comment: $v2->comment ?? $v1->comment,
            composer: $v2->composer ?? null,
            disc_number: $v2->part_of_a_set ?? null,
            genre: $v2->genre ?? $v1->genre,
            is_compilation: $v2->part_of_a_compilation === '1',
            title: $v2->title ?? $v1->title,
            track_number: $v2->track_number ?? $v1->track_number,
            year: (int) ($v2->year ?? $v1->year),
            copyright: $v2->copyright ?? null,
            description: $v2->text ?? null,
            lyrics: $v2->unsynchronised_lyric ?? null,
            language: $v2->language ?? null,
        );
    }

    public static function fromId3v2(Tag\Id3TagAudioV2 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album,
            artist: $tag->artist,
            album_artist: $tag->band,
            comment: $tag->comment,
            composer: $tag->composer,
            disc_number: $tag->part_of_a_set,
            genre: $tag->genre,
            is_compilation: $tag->part_of_a_compilation === '1',
            title: $tag->title,
            track_number: $tag->track_number,
            year: (int) $tag->year,
        );
    }

    public static function fromId3v1(Tag\Id3TagAudioV1 $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album,
            artist: $tag->artist,
            comment: $tag->comment,
            genre: $tag->genre,
            title: $tag->title,
            track_number: $tag->track_number,
            year: (int) $tag->year,
        );
    }

    public static function fromQuicktime(Tag\Id3TagQuicktime $tag): AudioCore
    {
        $creation_date = $tag->creation_date;
        $description = $tag->description;
        $description_long = $tag->description_long;

        $core = new AudioCore(
            title: $tag->title,
            artist: $tag->artist,
            album: $tag->album,
            genre: $tag->genre,
            track_number: $tag->track_number,
            disc_number: $tag->disc_number,
            composer: $tag->composer,
            is_compilation: $tag->compilation === '1',
            comment: $tag->comment,
            album_artist: $tag->album_artist,
            encoding_by: $tag->encoded_by,
            encoding: $tag->encoding_tool,
            language: $tag->language,
        );

        if ($creation_date) {
            if (strlen($creation_date) === 4) {
                $core->setYear((int) $creation_date);
            } else {
                try {
                    $parsedCreationDate = new \DateTimeImmutable($creation_date);
                } catch (\Exception $e) {
                    // ignore the issue so the rest of the data will be available
                }

                if (! empty($parsedCreationDate)) {
                    $core->setCreationDate($parsedCreationDate->format('Y-m-d\TH:i:s\Z'));
                    $core->setYear((int) $parsedCreationDate->format('Y'));
                }
            }
        }

        $core->setCopyright($tag->copyright);
        $core->setDescription($description);
        $core->setPodcastDescription($description_long);
        $core->setLyrics($tag->lyrics);

        return $core;
    }

    public static function fromVorbisComment(Tag\Id3TagVorbisComment $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title,
            artist: $tag->artist,
            album: $tag->album,
            genre: $tag->genre,
            track_number: $tag->tracknumber,
            comment: $tag->comment,
            album_artist: $tag->albumartist,
            composer: $tag->composer,
            disc_number: $tag->discnumber,
            is_compilation: $tag->compilation === '1',
            year: (int) $tag->date,
            encoding: $tag->encoder,
            description: $tag->description,
        );
    }

    public static function fromAsf(Tag\Id3TagAsf $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title,
            artist: $tag->artist,
            album: $tag->album,
            album_artist: $tag->albumartist,
            composer: $tag->composer,
            disc_number: $tag->partofset,
            genre: $tag->genre,
            track_number: $tag->track_number,
            year: (int) $tag->year,
            encoding: $tag->encodingsettings,
        );
    }

    public static function fromMatroska(Tag\Id3TagMatroska $tag): AudioCore
    {
        return new AudioCore(
            title: $tag->title,
            album: $tag->album,
            artist: $tag->artist,
            album_artist: $tag->album_artist,
            comment: $tag->comment,
            composer: $tag->composer,
            disc_number: $tag->disc,
            genre: $tag->genre,
            is_compilation: $tag->compilation === 'true',
            track_number: $tag->part_number,
            year: (int) $tag->date,
            encoding: $tag->encoder,
        );
    }

    public static function fromApe(Tag\Id3TagApe $tag): AudioCore
    {
        return new AudioCore(
            album: $tag->album,
            artist: $tag->artist,
            album_artist: $tag->album_artist,
            comment: $tag->comment,
            composer: $tag->composer,
            disc_number: $tag->disc,
            genre: $tag->genre,
            is_compilation: $tag->compilation === '1',
            title: $tag->title,
            track_number: $tag->track,
            creation_date: $tag->date,
            year: $tag->year ?? (int) $tag->date,
            encoding: $tag->encoder,
            description: $tag->description,
            copyright: $tag->copyright,
            lyrics: $tag->lyrics,
            synopsis: $tag->podcastdesc,
            language: $tag->language,
        );
    }
}
