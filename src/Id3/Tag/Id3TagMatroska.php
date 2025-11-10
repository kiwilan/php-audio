<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagMatroska extends Id3Tag
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $muxingapp = null,
        public readonly ?string $writingapp = null,
        public readonly ?string $album = null,
        public readonly ?string $artist = null,
        public readonly ?string $album_artist = null,
        public readonly ?string $comment = null,
        public readonly ?string $composer = null,
        public readonly ?string $disc = null,
        public readonly ?string $genre = null,
        public readonly ?string $compilation = null,
        public readonly ?string $part_number = null,
        public readonly ?string $date = null,
        public readonly ?string $encoder = null,
        public readonly ?string $duration = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: self::parseTag($metadata, 'title'),
            muxingapp: self::parseTag($metadata, 'muxingapp'),
            writingapp: self::parseTag($metadata, 'writingapp'),
            album: self::parseTag($metadata, 'album'),
            artist: self::parseTag($metadata, 'artist'),
            album_artist: self::parseTag($metadata, 'album_artist'),
            comment: self::parseTag($metadata, 'comment'),
            composer: self::parseTag($metadata, 'composer'),
            disc: self::parseTag($metadata, 'disc'),
            genre: self::parseTag($metadata, 'genre'),
            compilation: self::parseTag($metadata, 'compilation'),
            part_number: self::parseTag($metadata, 'part_number'),
            date: self::parseTag($metadata, 'date'),
            encoder: self::parseTag($metadata, 'encoder'),
            duration: self::parseTag($metadata, 'duration'),
        );

        return $self;
    }
}
