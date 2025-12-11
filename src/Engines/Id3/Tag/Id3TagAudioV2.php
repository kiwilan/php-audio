<?php

namespace Kiwilan\Audio\Id3\Tag;

class Id3TagAudioV2 extends Id3Tag
{
    public function __construct(
        public readonly ?string $album = null,
        public readonly ?string $artist = null,
        public readonly ?string $band = null,
        public readonly ?string $comment = null,
        public readonly ?string $composer = null,
        public readonly ?string $part_of_a_set = null,
        public readonly ?string $genre = null,
        public readonly ?string $part_of_a_compilation = null,
        public readonly ?string $title = null,
        public readonly ?string $track_number = null,
        public readonly ?string $year = null,
        public readonly ?string $copyright = null,
        public readonly ?string $unsynchronised_lyric = null,
        public readonly ?string $language = null,
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
