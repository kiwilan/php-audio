<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagQuicktime extends Id3Tag
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $track_number = null,
        public readonly ?string $disc_number = null,
        public readonly ?string $compilation = null,
        public readonly ?string $album = null,
        public readonly ?string $genre = null,
        public readonly ?string $composer = null,
        public readonly ?string $creation_date = null,
        public readonly ?string $copyright = null,
        public readonly ?string $artist = null,
        public readonly ?string $album_artist = null,
        public readonly ?string $encoded_by = null,
        public readonly ?string $encoding_tool = null,
        public readonly ?string $description = null,
        public readonly ?string $description_long = null,
        public readonly ?string $language = null,
        public readonly ?string $lyrics = null,
        public readonly ?string $comment = null,
        public readonly ?string $stik = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            title: self::parseTag($metadata, 'title'),
            track_number: self::parseTag($metadata, 'track_number'),
            disc_number: self::parseTag($metadata, 'disc_number'),
            compilation: self::parseTag($metadata, 'compilation'),
            album: self::parseTag($metadata, 'album'),
            genre: self::parseTag($metadata, 'genre'),
            composer: self::parseTag($metadata, 'composer'),
            creation_date: self::parseTag($metadata, 'creation_date'),
            copyright: self::parseTag($metadata, 'copyright'),
            artist: self::parseTag($metadata, 'artist'),
            album_artist: self::parseTag($metadata, 'album_artist'),
            encoded_by: self::parseTag($metadata, 'encoded_by'),
            encoding_tool: self::parseTag($metadata, 'encoding_tool'),
            description: self::parseTag($metadata, 'description'),
            description_long: self::parseTag($metadata, 'description_long'),
            language: self::parseTag($metadata, 'language'),
            lyrics: self::parseTag($metadata, 'lyrics'),
            comment: self::parseTag($metadata, 'comment'),
            stik: self::parseTag($metadata, 'stik'),
        );

        return $self;
    }
}
