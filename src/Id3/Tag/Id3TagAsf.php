<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAsf
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
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            albumartist: $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            partofset: $metadata['partofset'] ?? null,
            genre: $metadata['genre'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            year: $metadata['year'] ?? null,
            encodingsettings: $metadata['encodingsettings'] ?? null,
        );

        return $self;
    }
}
