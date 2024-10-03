<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAsf extends Id3Tag
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $artist = null,
        readonly public ?string $album = null,
        readonly public ?string $albumartist = null,
        readonly public ?string $composer = null,
        readonly public ?string $partofset = null,
        readonly public ?string $genre = null,
        readonly public ?string $track_number = null,
        readonly public ?string $year = null,
        readonly public ?string $encodingsettings = null,
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
