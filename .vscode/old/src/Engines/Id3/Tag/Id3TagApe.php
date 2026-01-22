<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagApe extends Id3Tag
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $artist = null,
        public readonly ?string $album = null,
        public readonly ?string $album_artist = null,
        public readonly ?string $composer = null,
        public readonly ?string $comment = null,
        public readonly ?string $genre = null,
        public readonly ?string $disc = null,
        public readonly ?string $compilation = null,
        public readonly ?string $track = null,
        public readonly ?string $date = null,
        public readonly ?string $encoder = null,
        public readonly ?string $description = null,
        public readonly ?string $copyright = null,
        public readonly ?string $lyrics = null,
        public readonly ?string $podcastdesc = null,
        public readonly ?string $language = null,
        public readonly ?string $year = null,
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
