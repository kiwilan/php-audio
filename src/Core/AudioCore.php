<?php

namespace Kiwilan\Audio\Core;

use Kiwilan\Audio\Id3\Tag;

class AudioCore
{
    public function __construct(
        public ?string $title = null,
        public ?string $artist = null,
        public ?string $album = null,
        public ?string $genre = null,
        public ?int $year = null,
        public ?string $track_number = null,
        public ?string $comment = null,
        public ?string $album_artist = null,
        public ?string $composer = null,
        public ?string $disc_number = null,
        public ?bool $is_compilation = null,
        public ?string $creation_date = null,
        public ?string $copyright = null,
        public ?string $encoding_by = null,
        public ?string $encoding = null,
        public ?string $description = null,
        public ?string $synopsis = null,
        public ?string $language = null,
        public ?string $lyrics = null,
        public bool $has_cover = false,
        public ?AudioCoreCover $cover = null,
    ) {}

    public function toArray(): array
    {
        // parse all properties
        $properties = get_object_vars($this);

        // filter out null values
        $properties = array_filter($properties, fn ($value) => $value !== null);
        $properties = array_filter($properties, fn ($value) => $value !== '');

        return $properties;
    }

    private function parseCompilation(AudioCore $core): ?string
    {
        if ($core->is_compilation === null) {
            return null;
        }

        return $core->is_compilation ? '1' : '0';
    }

    public static function toId3v2(AudioCore $core): Tag\Id3TagAudioV2
    {
        return new Tag\Id3TagAudioV2(
            album: $core->album,
            artist: $core->artist,
            band: $core->album_artist,
            comment: $core->comment,
            composer: $core->composer,
            part_of_a_set: $core->disc_number,
            genre: $core->genre,
            part_of_a_compilation: $core->parseCompilation($core),
            title: $core->title,
            track_number: $core->track_number,
            year: (string) $core->year,
            copyright: $core->copyright,
            unsynchronised_lyric: $core->lyrics,
            language: $core->language,
        );
    }

    public static function toId3v1(AudioCore $core): Tag\Id3TagAudioV1
    {
        return new Tag\Id3TagAudioV1(
            album: $core->album,
            artist: $core->artist,
            comment: $core->comment,
            genre: $core->genre,
            title: $core->title,
            track_number: $core->track_number,
            year: (string) $core->year,
        );
    }

    public static function toVorbisComment(AudioCore $core): Tag\Id3TagVorbisComment
    {
        return new Tag\Id3TagVorbisComment(
            album: $core->album,
            artist: $core->artist,
            albumartist: $core->album_artist,
            comment: $core->comment,
            composer: $core->composer,
            compilation: $core->parseCompilation($core),
            discnumber: $core->disc_number,
            genre: $core->genre,
            title: $core->title,
            tracknumber: $core->track_number,
            date: (string) $core->year,
            encoder: $core->encoding,
            description: $core->description,
        );
    }

    public static function toQuicktime(AudioCore $core): Tag\Id3TagQuicktime
    {
        return new Tag\Id3TagQuicktime(
            title: $core->title,
            track_number: $core->track_number,
            disc_number: $core->disc_number,
            compilation: $core->parseCompilation($core),
            album: $core->album,
            genre: $core->genre,
            composer: $core->composer,
            creation_date: $core->creation_date,
            copyright: $core->copyright,
            artist: $core->artist,
            album_artist: $core->album_artist,
            encoded_by: $core->encoding,
            encoding_tool: $core->encoding,
            description: $core->description,
            description_long: $core->synopsis,
            lyrics: $core->lyrics,
            comment: $core->comment,
        );
    }

    public static function toMatroska(AudioCore $core): Tag\Id3TagMatroska
    {
        return new Tag\Id3TagMatroska(
            title: $core->title,
            album: $core->album,
            artist: $core->artist,
            album_artist: $core->album_artist,
            comment: $core->comment,
            composer: $core->composer,
            disc: $core->disc_number,
            compilation: $core->parseCompilation($core),
            genre: $core->genre,
            part_number: $core->track_number,
            date: (string) $core->year,
            encoder: $core->encoding,
        );
    }

    public static function toApe(AudioCore $core): Tag\Id3TagApe
    {
        return new Tag\Id3TagApe(
            album: $core->album,
            artist: $core->artist,
            album_artist: $core->album_artist,
            comment: $core->comment,
            composer: $core->composer,
            disc: $core->disc_number,
            compilation: $core->parseCompilation($core),
            genre: $core->genre,
            title: $core->title,
            track: $core->track_number,
            date: (string) $core->year,
            encoder: $core->encoding,
        );
    }

    public static function toAsf(AudioCore $core): Tag\Id3TagAsf
    {
        return new Tag\Id3TagAsf(
            album: $core->album,
            artist: $core->artist,
            albumartist: $core->album_artist,
            composer: $core->composer,
            partofset: $core->disc_number,
            genre: $core->genre,
            track_number: $core->track_number,
            year: (string) $core->year,
            encodingsettings: $core->encoding,
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
        $date = $tag->creation_date;
        $description = $tag->description;
        $description_long = $tag->description_long;

        $creation_date = null;
        $year = null;

        if ($date) {
            if (strlen($date) === 4) {
                $year = (int) $date;
            } else {
                try {
                    $parsedCreationDate = new \DateTimeImmutable($date);
                } catch (\Exception $e) {
                    // ignore the issue so the rest of the data will be available
                }

                if (! empty($parsedCreationDate)) {
                    $creation_date = $parsedCreationDate->format('Y-m-d\TH:i:s\Z');
                    $year = (int) $parsedCreationDate->format('Y');
                }
            }
        }

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
            copyright: $tag->copyright,
            description: $description,
            synopsis: $description_long,
            lyrics: $tag->lyrics,
            creation_date: $creation_date,
            year: $year,
        );

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
