<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagVorbisComment extends Id3Tag
{
    public function __construct(
        public readonly ?string $description = null,
        public readonly ?string $encoder = null,
        public readonly ?string $title = null,
        public readonly ?string $artist = null,
        public readonly ?string $album = null,
        public readonly ?string $genre = null,
        public readonly ?string $comment = null,
        public readonly ?string $albumartist = null,
        public readonly ?string $composer = null,
        public readonly ?string $discnumber = null,
        public readonly ?string $compilation = null,
        public readonly ?string $date = null,
        public readonly ?string $tracknumber = null,
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
