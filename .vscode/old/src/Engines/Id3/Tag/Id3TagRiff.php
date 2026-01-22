<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagRiff extends Id3Tag
{
    public function __construct(
        public readonly ?string $artist = null,
        public readonly ?string $comment = null,
        public readonly ?string $creationdate = null,
        public readonly ?string $genre = null,
        public readonly ?string $title = null,
        public readonly ?string $product = null,
        public readonly ?string $software = null,
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
