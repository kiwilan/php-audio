<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagVorbisComment extends Id3Tag
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
            description: self::parseTag($metadata, 'description'),
            encoder: self::parseTag($metadata, 'encoder'),
            title: self::parseTag($metadata, 'title'),
            artist: self::parseTag($metadata, 'artist'),
            album: self::parseTag($metadata, 'album'),
            genre: self::parseTag($metadata, 'genre'),
            comment: self::parseTag($metadata, 'comment'),
            albumartist: self::parseTag($metadata, 'albumartist'),
            composer: self::parseTag($metadata, 'composer'),
            discnumber: self::parseTag($metadata, 'discnumber'),
            compilation: self::parseTag($metadata, 'compilation'),
            date: self::parseTag($metadata, 'date'),
            tracknumber: self::parseTag($metadata, 'tracknumber'),
        );

        return $self;
    }
}
