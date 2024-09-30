<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagQuicktime extends Id3Tag
{
    public function __construct(
        readonly public ?string $title = null,
        readonly public ?string $track_number = null,
        readonly public ?string $disc_number = null,
        readonly public ?string $compilation = null,
        readonly public ?string $album = null,
        readonly public ?string $genre = null,
        readonly public ?string $composer = null,
        readonly public ?string $creation_date = null,
        readonly public ?string $copyright = null,
        readonly public ?string $artist = null,
        readonly public ?string $album_artist = null,
        readonly public ?string $encoded_by = null,
        readonly public ?string $encoding_tool = null,
        readonly public ?string $description = null,
        readonly public ?string $description_long = null,
        readonly public ?string $language = null,
        readonly public ?string $lyrics = null,
        readonly public ?string $comment = null,
        readonly public ?string $stik = null,
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
