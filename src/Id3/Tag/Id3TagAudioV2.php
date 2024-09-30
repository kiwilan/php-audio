<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAudioV2 extends Id3Tag
{
    public function __construct(
        readonly public ?string $album = null,
        readonly public ?string $artist = null,
        readonly public ?string $band = null,
        readonly public ?string $comment = null,
        readonly public ?string $composer = null,
        readonly public ?string $part_of_a_set = null,
        readonly public ?string $genre = null,
        readonly public ?string $part_of_a_compilation = null,
        readonly public ?string $title = null,
        readonly public ?string $track_number = null,
        readonly public ?string $year = null,
        readonly public ?string $copyright = null,
        readonly public ?string $unsynchronised_lyric = null,
        readonly public ?string $language = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $copyright = $metadata['copyright_message'] ?? $metadata['copyright'] ?? null;

        $self = new self(
            album: self::parseTag($metadata, 'album'),
            artist: self::parseTag($metadata, 'artist'),
            band: self::parseTag($metadata, 'band'),
            comment: self::parseTag($metadata, 'comment'),
            composer: self::parseTag($metadata, 'composer'),
            part_of_a_set: self::parseTag($metadata, 'part_of_a_set'),
            genre: self::parseTag($metadata, 'genre'),
            part_of_a_compilation: self::parseTag($metadata, 'part_of_a_compilation'),
            title: self::parseTag($metadata, 'title'),
            track_number: self::parseTag($metadata, 'track_number'),
            year: self::parseTag($metadata, 'year'),
            copyright: $copyright,
            unsynchronised_lyric: self::parseTag($metadata, 'unsynchronised_lyric'),
            language: self::parseTag($metadata, 'language'),
        );

        return $self;
    }
}
