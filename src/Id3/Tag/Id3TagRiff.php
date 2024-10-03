<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagRiff extends Id3Tag
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
            artist: self::parseTag($metadata, 'artist'),
            comment: self::parseTag($metadata, 'comment'),
            creationdate: self::parseTag($metadata, 'creationdate'),
            genre: self::parseTag($metadata, 'genre'),
            title: self::parseTag($metadata, 'title'),
            product: self::parseTag($metadata, 'product'),
            software: self::parseTag($metadata, 'software'),
        );

        return $self;
    }
}
