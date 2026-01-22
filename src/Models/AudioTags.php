<?php

namespace Kiwilan\Audio\Models;

/**
 * Represents audio tags from metadata,
 * like `title`, `artist`, `album`, `genre`
 */
class AudioTags
{
    public function __construct(
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
        protected array $raw = [],
    ) {}

    /**
     * Get `album` tag, like `The Wall`.
     */
    public function getAlbum(): ?string
    {
        return $this->album;
    }

    /**
     * Get `album_artist` tag, like `Pink Floyd`.
     *
     * - For `ffmpeg`: `album_artist`
     * - For `id3v2`: `band`
     * - For `asf`: `albumartist`
     * - For `vorbiscomment`: `albumartist`
     */
    public function getAlbumArtist(): ?string
    {
        return $this->album_artist;
    }

    /**
     * Get `artist` tag, like `Syd Barrett;Nick Mason;Roger Waters;Richard Wright;David Gilmour`.
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * Get `asin` tag, like `B008Y43GBY`.
     *
     * ASIN refer to Audible audiobook tag.
     */
    public function getASIN(): ?string
    {
        return $this->asin;
    }

    /**
     * Get `comment` tag, like `Recorded at Abbey Road Studios`.
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Get `compilation` tag, like `1`.
     *
     * To get `compilation` as `bool`, use `isCompilation()`.
     *
     * - For `ffmpeg`: `compilation`
     * - For `id3v2`: `part_of_a_compilation`
     * - For `quicktime`: `compilation`
     * - For `vorbiscomment`: `compilation`
     * - For `matroska`: `compilation`
     * - For `ape`: `compilation`
     */
    public function getCompilation(): ?string
    {
        return $this->compilation;
    }

    /**
     * Get `compilation` tag as `bool`.
     */
    public function isCompilation(): bool
    {
        return match ($this->compilation) {
            null => false,
            '1' => true,
            '0' => false,
            'true' => true,
            'false' => false,
            default => false,
        };
    }

    /**
     * Get `composer` tag, like `Syd Barrett`.
     */
    public function getComposer(): ?string
    {
        return $this->composer;
    }

    /**
     * Get `copyright` tag, like `© 1979 Pink Floyd`.
     */
    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    /**
     * Get `description` tag, like `The Wall is the eleventh studio album by the English rock band Pink Floyd`.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get `disc` tag, like `1`.
     *
     * - For `ffmpeg`: `compilation`
     * - For `id3v2`: `part_of_a_set`
     * - For `asf`: `partofset`
     * - For `vorbiscomment`: `discnumber`
     * - For `matroska`: `disc`
     * - For `ape`: `disc`
     */
    public function getDisc(): ?string
    {
        return $this->disc;
    }

    /**
     * Get `disc` tag as integer, like `1`.
     */
    public function getDiscNumber(): ?int
    {
        if (str_contains($this->disc, '/')) {
            $disc_number = explode('/', $this->disc);

            return intval($disc_number[0]);
        }

        return $this->disc ? intval($this->disc) : null;
    }

    /**
     * Get `encoded_by` tag, like `iTunes`.
     */
    public function getEncodedBy(): ?string
    {
        return $this->encoded_by;
    }

    /**
     * Get `encoder` tag, like `Lavf62.3.100`.
     */
    public function getEncoder(): ?string
    {
        return $this->encoder;
    }

    /**
     * Get `genre` tag, like `Progressive Rock;Rock Opera`.
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Get `genre` tag as array, like `['Progressive Rock', 'Rock Opera']` for `Progressive Rock/Rock Opera` or `Progressive Rock;Rock Opera`.
     *
     * @param  string  $split_by  Seperator of genres, like `;`
     * @return string[]
     */
    public function getGenres(string $split_by): array
    {
        if (! str_contains($this->genre, $split_by)) {
            return [$this->genre];
        }

        return explode($split_by, $this->genre);
    }

    /**
     * Get `isbn` tag, like `9780007496785`.
     *
     * ISBN refer to book tag.
     */
    public function getISBN(): ?string
    {
        return $this->isbn;
    }

    /**
     * Get `language` tag, like `English`.
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Get `lyrics` tag, like `Hey! Teachers! Leave them kids alone!`.
     */
    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    /**
     * Get `publisher` tag, like `Pink Floyd Music Publishers Ltd.`.
     */
    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    /**
     * Get `series` tag, like `The Wall Saga`.
     *
     * Useful for audiobook.
     */
    public function getSeries(): ?string
    {
        return $this->series;
    }

    /**
     * Get `series_part` tag, like `1`.
     *
     * Useful for audiobook.
     */
    public function getSeriesPart(): ?string
    {
        return $this->series_part;
    }

    /**
     * Get `series_part` tag as `int`.
     */
    public function getVolume(): ?int
    {
        if (! $this->series_part) {
            return null;
        }

        return intval($this->series_part);
    }

    /**
     * Get `subtitle` tag, like `All in all, it's just another brick in the wall.`.
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * Get `synopsis` tag, like `The Wall is one of the most iconic concept albums and rock operas in music history.`.
     *
     * `description` and `synopsis` are not the same tag, but for many formats, they are the same.
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * Get `title` tag, like `Another Brick in the Wall, Part 1`.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get `track` tag, like `3/13`.
     *
     * - For `ffmpeg`: `track`
     * - For `vorbiscomment`: `track_number`
     * - For `matroska`: `part_number`
     * - For `ape`: `track`
     */
    public function getTrack(): ?string
    {
        return $this->track;
    }

    /**
     * Get `track` tag as integer, like `1`.
     */
    public function getTrackNumber(): ?int
    {
        return $this->track ? intval($this->track) : null;
    }

    /**
     * Get `date` tag, like `1979-11-30`.
     *
     * - For `ffmpeg`: `date`
     * - For `matroska`: `date`
     * - For `ape`: `date`
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Get `date` tag as year.
     */
    public function getYear(): ?int
    {
        if (! $this->date) {
            return null;
        }

        $dt = new \DateTime($this->date);
        $year = $dt->format('Y');
        if (! $year) {
            return null;
        }

        return intval($year);
    }

    /**
     * Raw data from engine.
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    public function __set(string $name, mixed $value): void
    {
        try {
            $this->{$name} = $value;
        } catch (\Throwable $th) {
            // throw $th;
        }
    }
}
