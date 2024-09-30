<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagApe
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

        $self = new self(
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            album_artist: $metadata['album_artist'] ?? $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            comment: $metadata['comment'] ?? null,
            genre: $metadata['genre'] ?? null,
            disc: $metadata['disc'] ?? $metadata['discnumber'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            track: $metadata['track'] ?? null,
            date: $metadata['date'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            description: $metadata['description'] ?? null,
            copyright: $metadata['copyright'] ?? null,
            lyrics: $metadata['unsyncedlyrics'] ?? null,
            podcastdesc: $metadata['podcastdesc'] ?? null,
            language: $metadata['language'] ?? null,
            year: $metadata['year'] ?? null,
        );

        return $self;
    }
}
