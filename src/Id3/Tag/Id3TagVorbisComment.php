<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagVorbisComment
{
    public function __construct(
        readonly public ?string $description = null,
        readonly public ?string $encoder = null,
        readonly public ?string $title = null,
        readonly public ?string $artist = null,
        readonly public ?string $album = null,
        readonly public ?string $genre = null,
        readonly public ?string $comment = null,
        readonly public ?string $albumartist = null,
        readonly public ?string $composer = null,
        readonly public ?string $discnumber = null,
        readonly public ?string $compilation = null,
        readonly public ?string $date = null,
        readonly public ?string $tracknumber = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            description: $metadata['description'] ?? null,
            encoder: $metadata['encoder'] ?? null,
            title: $metadata['title'] ?? null,
            artist: $metadata['artist'] ?? null,
            album: $metadata['album'] ?? null,
            genre: $metadata['genre'] ?? null,
            comment: $metadata['comment'] ?? null,
            albumartist: $metadata['albumartist'] ?? null,
            composer: $metadata['composer'] ?? null,
            discnumber: $metadata['discnumber'] ?? null,
            compilation: $metadata['compilation'] ?? null,
            date: $metadata['date'] ?? null,
            tracknumber: $metadata['tracknumber'] ?? null,
        );

        return $self;
    }
}
