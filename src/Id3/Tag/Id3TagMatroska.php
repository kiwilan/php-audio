<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagMatroska extends Id3Tag
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
