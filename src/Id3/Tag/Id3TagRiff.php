<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagRiff
{
    public function __construct(
        readonly public ?string $artist = null,
        readonly public ?string $comment = null,
        readonly public ?string $creationdate = null,
        readonly public ?string $genre = null,
        readonly public ?string $title = null,
        readonly public ?string $product = null,
        readonly public ?string $software = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }
        $self = new self(
            artist: $metadata['artist'] ?? null,
            comment: $metadata['comment'] ?? null,
            creationdate: $metadata['creationdate'] ?? null,
            genre: $metadata['genre'] ?? null,
            title: $metadata['title'] ?? null,
            product: $metadata['product'] ?? null,
            software: $metadata['software'] ?? null,
        );

        return $self;
    }
}
