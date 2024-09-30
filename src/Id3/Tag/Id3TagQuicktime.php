<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagQuicktime
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $track_number = null,
        readonly public ?string $disc_number = null,
        readonly public ?string $compilation = null,
        readonly public ?string $album = null,
        readonly public ?string $genre = null,
        readonly public ?string $composer = null,
        readonly public ?string $creation_date = null,
        readonly public ?string $copyright = null,
        readonly public ?string $artist = null,
        readonly public ?string $album_artist = null,
        readonly public ?string $encoded_by = null,
        readonly public ?string $encoding_tool = null,
        readonly public ?string $description = null,
        readonly public ?string $description_long = null,
        readonly public ?string $language = null,
        readonly public ?string $lyrics = null,
        readonly public ?string $comment = null,
        readonly public ?string $stik = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: $metadata['title'] ?? null,
            track_number: $metadata['track_number'] ?? null,
            disc_number: $metadata['disc_number'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            album: $metadata['album'] ?? null,
            genre: $metadata['genre'] ?? null,
            composer: $metadata['composer'] ?? null,
            creation_date: $metadata['creation_date'] ?? null,
            copyright: $metadata['copyright'] ?? null,
            artist: $metadata['artist'] ?? null,
            album_artist: $metadata['album_artist'] ?? null,
            encoded_by: $metadata['encoded_by'] ?? null,
            encoding_tool: $metadata['encoding_tool'] ?? null,
            description: $metadata['description'] ?? null,
            description_long: $metadata['description_long'] ?? null,
            language: $metadata['language'] ?? null,
            lyrics: $metadata['lyrics'] ?? null,
            comment: $metadata['comment'] ?? null,
            stik: $metadata['stik'] ?? null,
        );

        return $self;
    }
}
