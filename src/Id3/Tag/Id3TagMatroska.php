<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagMatroska
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $muxingapp = null,
        readonly public ?string $writingapp = null,
        readonly public ?string $album = null,
        readonly public ?string $artist = null,
        readonly public ?string $album_artist = null,
        readonly public ?string $comment = null,
        readonly public ?string $composer = null,
        readonly public ?string $disc = null,
        readonly public ?string $genre = null,
        readonly public ?string $compilation = null,
        readonly public ?string $part_number = null,
        readonly public ?string $date = null,
        readonly public ?string $encoder = null,
        readonly public ?string $duration = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            muxingapp: $metadata['muxingapp'] ?? null,
            writingapp: $metadata['writingapp'] ?? null,
            album: $metadata['album'] ?? null,
            artist: $metadata['artist'] ?? null,
            album_artist: $metadata['album_artist'] ?? null,
            comment: $metadata['comment'] ?? null,
            composer: $metadata['composer'] ?? null,
            disc: $metadata['disc'] ?? null,
            genre: $metadata['genre'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            part_number: $metadata['part_number'] ?? null,
            date: $metadata['date'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            duration: $metadata['duration'] ?? null,
        );

        return $self;
    }
}
