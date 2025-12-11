<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAsf extends Id3Tag
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $artist = null,
        public readonly ?string $album = null,
        public readonly ?string $albumartist = null,
        public readonly ?string $composer = null,
        public readonly ?string $partofset = null,
        public readonly ?string $genre = null,
        public readonly ?string $track_number = null,
        public readonly ?string $year = null,
        public readonly ?string $encodingsettings = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            title: self::parseTag($metadata, 'title'),
            artist: self::parseTag($metadata, 'artist'),
            album: self::parseTag($metadata, 'album'),
            albumartist: self::parseTag($metadata, 'albumartist'),
            composer: self::parseTag($metadata, 'composer'),
            partofset: self::parseTag($metadata, 'partofset'),
            genre: self::parseTag($metadata, 'genre'),
            track_number: self::parseTag($metadata, 'track_number'),
            year: self::parseTag($metadata, 'year'),
            encodingsettings: self::parseTag($metadata, 'encodingsettings'),
        );

        return $self;
    }
}
