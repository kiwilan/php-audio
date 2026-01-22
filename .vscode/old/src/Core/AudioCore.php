<?php

namespace Kiwilan\Audio\Core;

class AudioCore
{
    public function __construct(
        public readonly ?AudioCoreCover $cover = null,
        public readonly bool $has_cover = false,

        // public readonly ?string $creation_date = null,

        protected ?string $album = null, // The Wall
        protected ?string $album_artist = null, // Pink Floyd
        protected ?string $artist = null, // Syd Barrett;Nick Mason;Roger Waters;Richard Wright;David Gilmour
        protected ?string $asin = null, // B008Y43GBY
        protected ?string $comment = null, // Recorded at Abbey Road Studios
        protected ?string $compilation = null, // 1
        protected ?string $composer = null, // Syd Barrett
        protected ?string $copyright = null, // © 1979 Pink Floyd
        protected ?string $description = null, // The Wall is the eleventh studio album by the English rock band Pink Floyd
        protected ?string $disc = null, // 1/2
        protected ?string $encoded_by = null, // iTunes
        protected ?string $encoder = null, // Lavf62.3.100
        protected ?string $genre = null, // Progressive Rock;Rock Opera
        protected ?string $isbn = null, // 9780007496785
        protected ?string $language = null, // English
        protected ?string $lyrics = null, // Hey! Teachers! Leave them kids alone!
        protected ?string $publisher = null, // Pink Floyd Music Publishers Ltd.
        protected ?string $series = null, // The Wall Saga
        protected ?string $series_part = null, // 1
        protected ?string $subtitle = null, // All in all, it's just another brick in the wall.
        protected ?string $synopsis = null, // The Wall is one of the most iconic concept albums and rock operas in music history.
        protected ?string $title = null, // Another Brick in the Wall, Part 1
        protected ?string $track = null, // 3/13
        protected ?string $date = null, // 1979-11-30
    ) {}

    public function toArray(): array
    {
        // parse all properties
        $properties = get_object_vars($this);

        // filter out null values
        $properties = array_filter($properties, fn ($value) => $value !== null);
        $properties = array_filter($properties, fn ($value) => $value !== '');

        return $properties;
    }

    // public function parseCompilation(AudioCore $core): ?string
    // {
    //     if ($core->is_compilation === null) {
    //         return null;
    //     }

    //     return $core->is_compilation ? '1' : '0';
    // }
}
