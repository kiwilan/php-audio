<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagApe extends Id3Tag
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $artist = null,
        readonly public ?string $album = null,
        readonly public ?string $album_artist = null,
        readonly public ?string $composer = null,
        readonly public ?string $comment = null,
        readonly public ?string $genre = null,
        readonly public ?string $disc = null,
        readonly public ?string $compilation = null,
        readonly public ?string $track = null,
        readonly public ?string $date = null,
        readonly public ?string $encoder = null,
        readonly public ?string $description = null,
        readonly public ?string $copyright = null,
        readonly public ?string $lyrics = null,
        readonly public ?string $podcastdesc = null,
        readonly public ?string $language = null,
        readonly public ?string $year = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $album_artist = $metadata['album_artist'] ?? $metadata['albumartist'] ?? null;
        $disc = $metadata['disc'] ?? $metadata['discnumber'] ?? null;

        $self = new self(
            title: self::parseTag($metadata, 'title'),
            artist: self::parseTag($metadata, 'artist'),
            album: self::parseTag($metadata, 'album'),
            album_artist: $album_artist,
            composer: self::parseTag($metadata, 'composer'),
            comment: self::parseTag($metadata, 'comment'),
            genre: self::parseTag($metadata, 'genre'),
            disc: $disc,
            compilation: self::parseTag($metadata, 'compilation'),
            track: self::parseTag($metadata, 'track'),
            date: self::parseTag($metadata, 'date'),
            encoder: self::parseTag($metadata, 'encoder'),
            description: self::parseTag($metadata, 'description'),
            copyright: self::parseTag($metadata, 'copyright'),
            lyrics: self::parseTag($metadata, 'unsyncedlyrics'),
            podcastdesc: self::parseTag($metadata, 'podcastdesc'),
            language: self::parseTag($metadata, 'language'),
            year: self::parseTag($metadata, 'year'),
        );

        return $self;
    }
}
