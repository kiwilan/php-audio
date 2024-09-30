<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAudioV2
{
    public function __construct(
        readonly public ?string $album = null,
        readonly public ?string $artist = null,
        readonly public ?string $band = null,
        readonly public ?string $comment = null,
        readonly public ?string $composer = null,
        readonly public ?string $part_of_a_set = null,
        readonly public ?string $genre = null,
        readonly public ?string $part_of_a_compilation = null,
        readonly public ?string $title = null,
        readonly public ?string $track_number = null,
        readonly public ?string $year = null,
        readonly public ?string $copyright = null,
        readonly public ?string $text = null,
        readonly public ?string $unsynchronised_lyric = null,
        readonly public ?string $language = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            album: $metadata['album'] ?? null,
            artist: $metadata['artist'] ?? null,
            band: $metadata['band'] ?? null,
            comment: $metadata['comment'] ?? null,
            composer: $metadata['composer'] ?? null,
            part_of_a_set: $metadata['part_of_a_set'] ?? null,
            genre: $metadata['genre'] ?? null,
            part_of_a_compilation: $metadata['part_of_a_compilation'] ?? null,
            title: $metadata['title'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            year: $metadata['year'] ?? null,
            copyright: $metadata['copyright_message'] ?? null,
            text: $metadata['text'] ?? null,
            unsynchronised_lyric: $metadata['unsynchronised_lyric'] ?? null,
            language: $metadata['language'] ?? null,
        );

        return $self;
    }
}
