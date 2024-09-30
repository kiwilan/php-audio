<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAudioV1
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $artist = null,
        readonly public ?string $album = null,
        readonly public ?string $year = null,
        readonly public ?string $genre = null,
        readonly public ?string $comment = null,
        readonly public ?string $track_number = null,
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
            year: $metadata['year'] ?? null,
            genre: $metadata['genre'] ?? null,
            comment: $metadata['comment'] ?? null,
            track_number: $metadata['track_number'] ?? null,
        );

        return $self;
    }
}
