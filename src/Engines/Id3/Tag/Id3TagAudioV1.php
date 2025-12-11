<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAudioV1 extends Id3Tag
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $artist = null,
        public readonly ?string $album = null,
        public readonly ?string $year = null,
        public readonly ?string $genre = null,
        public readonly ?string $comment = null,
        public readonly ?string $track_number = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: self::parseTag($metadata, 'title'),
            artist: self::parseTag($metadata, 'artist'),
            album: self::parseTag($metadata, 'album'),
            year: self::parseTag($metadata, 'year'),
            genre: self::parseTag($metadata, 'genre'),
            comment: self::parseTag($metadata, 'comment'),
            track_number: self::parseTag($metadata, 'track_number'),
        );

        return $self;
    }
}
