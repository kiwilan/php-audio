<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;
use Kiwilan\Audio\Id3\Id3Reader;
use Kiwilan\Audio\Id3\Id3Writer;
use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;

class Audio
{
    /**
     * @param  array<string, string[]>  $raw_all
     */
    protected function __construct(
        protected string $path,
        protected string $extension,
        protected AudioFormatEnum $format,
        protected ?AudioTypeEnum $type = null,
        protected ?AudioMetadata $metadata = null,
        protected ?AudioCover $cover = null,
        protected ?float $duration = null,
        protected bool $is_writable = false,
        protected bool $is_valid = false,
        protected bool $has_cover = false,
        //
        protected ?string $title = null,
        protected ?string $artist = null,
        protected ?string $album = null,
        protected ?string $genre = null,
        protected ?int $year = null,
        protected ?string $track_number = null,
        protected ?string $comment = null,
        protected ?string $album_artist = null,
        protected ?string $composer = null,
        protected ?string $disc_number = null,
        protected bool $is_compilation = false,
        protected ?string $creation_date = null,
        protected ?string $copyright = null,
        protected ?string $encoding_by = null,
        protected ?string $encoding = null,
        protected ?string $description = null,
        protected ?string $synopsis = null,
        protected ?string $language = null,
        protected ?string $lyrics = null,

        protected array $raw_all = [],
    ) {}

    public static function read(string $path): self
    {
        $fileExists = file_exists($path);
        if (! $fileExists) {
            throw new \Exception("File not found: {$path}");
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        $format = AudioFormatEnum::tryFrom($extension);

        $self = new self(
            path: $path,
            extension: $extension,
            format: $format ? $format : AudioFormatEnum::unknown,
        );

        try {
            $id3_reader = Id3Reader::make($path);

            $self->metadata = AudioMetadata::make($self, $id3_reader);
            $self->duration = (float) number_format((float) $self->metadata->getDurationSeconds(), 2, '.', '');
            $self->is_writable = $id3_reader->isWritable();

            $self->parseTags($id3_reader);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
        }

        return $self;
    }

    /**
     * @deprecated Use `read()` method instead.
     *
     * Get audio file from path.
     */
    public static function get(string $path): self
    {
        return self::read($path);
    }

    /**
     * Get audio file path, like `/path/to/audio.mp3`.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get audio file extension, like `mp3`.
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get audio format if recognized, like `AudioFormatEnum::mp3`.
     */
    public function getFormat(): AudioFormatEnum
    {
        return $this->format;
    }

    /**
     * Get audio type if recognized, like `AudioTypeEnum::id3`.
     */
    public function getType(): ?AudioTypeEnum
    {
        return $this->type;
    }

    /**
     * Get audio metadata.
     */
    public function getMetadata(): ?AudioMetadata
    {
        return $this->metadata;
    }

    /**
     * Get audio cover.
     */
    public function getCover(): ?AudioCover
    {
        return $this->cover;
    }

    public function getId3Reader(): ?Id3Reader
    {
        return Id3Reader::make($this->path);
    }

    public function write(): Id3Writer
    {
        return Id3Writer::make($this);
    }

    /**
     * @deprecated Use `write()` method instead.
     *
     * Update audio file.
     */
    public function update(): Id3Writer
    {
        return $this->write();
    }

    /**
     * Get duration of the audio file in seconds, limited to 2 decimals, like `180.66`
     *
     * To get exact duration, use `getMetadata()->getDurationSeconds()` instead.
     */
    public function getDuration(): ?float
    {
        return $this->duration;
    }

    /**
     * Get duration of the audio file in human readable format, like `00:03:00`
     */
    public function getDurationHuman(): ?string
    {
        return gmdate('H:i:s', intval($this->duration));
    }

    /**
     * To know if the audio file is writable.
     */
    public function isWritable(): bool
    {
        return $this->is_writable;
    }

    /**
     * To know if the audio file is valid.
     */
    public function isValid(): bool
    {
        return $this->is_valid;
    }

    /**
     * To know if the audio file has cover.
     */
    public function hasCover(): bool
    {
        return $this->has_cover;
    }

    /**
     * Get `title` tag, like `Another Brick In The Wall`.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get `artist` tag, like `Pink Floyd`.
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * Get `album` tag, like `The Wall`.
     */
    public function getAlbum(): ?string
    {
        return $this->album;
    }

    /**
     * Get `genre` tag, like `Rock`.
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Get `year` tag, like `1979`.
     *
     * - For `matroska` format: `date` tag.
     * - For `ape` format: `date` tag.
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * Get `track_number` tag, like `1`.
     *
     * - For `vorbiscomment` format: `track_number` tag.
     * - For `matroska` format: `part_number` tag.
     * - For `ape` format: `track` tag.
     */
    public function getTrackNumber(): ?string
    {
        return $this->track_number;
    }

    /**
     * Get `track_number` tag as integer, like `1`.
     */
    public function getTrackNumberInt(): ?int
    {
        return $this->track_number ? intval($this->track_number) : null;
    }

    /**
     * Get `comment` tag, like `Recorded at Abbey Road Studios`.
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Get `album_artist` tag, like `Pink Floyd`.
     *
     * - For `id3v2` format: `band` tag.
     * - For `asf` format: `albumartist` tag.
     * - For `vorbiscomment` format: `albumartist` tag.
     */
    public function getAlbumArtist(): ?string
    {
        return $this->album_artist;
    }

    /**
     * Get `composer` tag, like `Roger Waters`.
     */
    public function getComposer(): ?string
    {
        return $this->composer;
    }

    /**
     * Get `disc_number` tag, like `1`.
     *
     * - For `id3v2` format: `part_of_a_set` tag.
     * - For `asf` format: `partofset` tag.
     * - For `vorbiscomment` format: `discnumber` tag.
     * - For `matroska` format: `disc` tag.
     * - For `ape` format: `disc` tag.
     */
    public function getDiscNumber(): ?string
    {
        return $this->disc_number;
    }

    /**
     * Get `disc_number` tag as integer, like `1`.
     */
    public function getDiscNumberInt(): ?int
    {
        if (str_contains($this->disc_number, '/')) {
            $disc_number = explode('/', $this->disc_number);

            return intval($disc_number[0]);
        }

        return $this->disc_number ? intval($this->disc_number) : null;
    }

    /**
     * To know if the audio file is a compilation.
     *
     * - For `id3v2` format: `part_of_a_compilation` tag.
     * - For `quicktime` format: `compilation` tag.
     * - For `vorbiscomment` format: `compilation` tag.
     * - For `matroska` format: `compilation` tag.
     * - For `ape` format: `compilation` tag.
     */
    public function isCompilation(): bool
    {
        return $this->is_compilation;
    }

    /**
     * Get `creation_date` tag, like `1979-11-30`.
     *
     * - For `matroska` format: `date` tag.
     * - For `ape` format: `date` tag.
     */
    public function getCreationDate(): ?string
    {
        return $this->creation_date;
    }

    /**
     * Get `encoding_by` tag, like `EAC`.
     */
    public function getEncodingBy(): ?string
    {
        return $this->encoding_by;
    }

    /**
     * Get `encoding` tag, like `LAME`.
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
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
     * Get `synopsis` tag, like `The Wall is the eleventh studio album by the English rock band Pink Floyd`.
     *
     * `description` and `synopsis` are not the same tag, but for many formats, they are the same.
     */
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    /**
     * Get `language` tag, like `en`.
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Get `lyrics` tag, like `We don't need no education`.
     */
    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    /**
     * Get raw tags as array with all formats.
     *
     * For example, for `mp3` format: `['id3v1' => [...], 'id3v2' => [...]]`.
     */
    public function getRawAll(): array
    {
        return $this->raw_all;
    }

    /**
     * Get raw tags as array with main format.
     *
     * For example, for `mp3` format, `id3v2` entry will be returned.
     *
     * @param  string|null  $format  If not provided, main format will be returned.
     * @return string[]
     */
    public function getRaw(?string $format = null): ?array
    {
        if ($format) {
            return $this->raw_all[$format] ?? null;
        }

        $tags = match ($this->type) {
            AudioTypeEnum::id3 => $this->raw_all['id3v2'] ?? [],
            AudioTypeEnum::vorbiscomment => $this->raw_all['vorbiscomment'] ?? [],
            AudioTypeEnum::quicktime => $this->raw_all['quicktime'] ?? [],
            AudioTypeEnum::matroska => $this->raw_all['matroska'] ?? [],
            AudioTypeEnum::ape => $this->raw_all['ape'] ?? [],
            AudioTypeEnum::asf => $this->raw_all['asf'] ?? [],
            default => [],
        };

        return $tags;
    }

    /**
     * Get raw tags key from main format.
     *
     * @param  string  $key  Key name.
     * @param  string|null  $format  If not provided, main format will be used.
     */
    public function getRawKey(string $key, ?string $format = null): string|int|bool|null
    {
        $tags = $this->getRaw($format);

        return $tags[$key] ?? null;
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'extension' => $this->extension,
            'format' => $this->format,
            'type' => $this->type,
            'metadata' => $this->metadata?->toArray(),
            'cover' => $this->cover?->toArray(),
            'duration' => $this->duration,
            'is_writable' => $this->is_writable,
            'is_valid' => $this->is_valid,
            'has_cover' => $this->has_cover,
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'genre' => $this->genre,
            'year' => $this->year,
            'track_number' => $this->track_number,
            'comment' => $this->comment,
            'album_artist' => $this->album_artist,
            'composer' => $this->composer,
            'disc_number' => $this->disc_number,
            'is_compilation' => $this->is_compilation,
            'creation_date' => $this->creation_date,
            'encoding_by' => $this->encoding_by,
            'encoding' => $this->encoding,
            'description' => $this->description,
            'synopsis' => $this->synopsis,
            'language' => $this->language,
            'lyrics' => $this->lyrics,
            'raw_all' => $this->raw_all,
        ];
    }

    private function parseTags(?\Kiwilan\Audio\Id3\Id3Reader $id3_reader): self
    {
        $this->type = match ($this->format) {
            AudioFormatEnum::aac => null,
            AudioFormatEnum::aif => AudioTypeEnum::id3,
            AudioFormatEnum::aifc => AudioTypeEnum::id3,
            AudioFormatEnum::aiff => AudioTypeEnum::id3,
            AudioFormatEnum::flac => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::m4a => AudioTypeEnum::quicktime,
            AudioFormatEnum::m4b => AudioTypeEnum::quicktime,
            AudioFormatEnum::m4v => AudioTypeEnum::quicktime,
            AudioFormatEnum::mka => AudioTypeEnum::matroska,
            AudioFormatEnum::mkv => AudioTypeEnum::matroska,
            AudioFormatEnum::mp3 => AudioTypeEnum::id3,
            AudioFormatEnum::mp4 => AudioTypeEnum::quicktime,
            AudioFormatEnum::ogg => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::opus => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::spx => AudioTypeEnum::vorbiscomment,
            AudioFormatEnum::tta => AudioTypeEnum::ape,
            AudioFormatEnum::wav => AudioTypeEnum::id3,
            AudioFormatEnum::webm => AudioTypeEnum::matroska,
            AudioFormatEnum::wma => AudioTypeEnum::asf,
            AudioFormatEnum::wv => AudioTypeEnum::ape,
            default => null,
        };

        $tags = $id3_reader->getTags();
        if (! $tags || $tags->is_empty) {
            return $this;
        }

        $raw_tags = $id3_reader->getRaw()['tags'] ?? [];
        foreach ($raw_tags as $name => $raw_tag) {
            $this->raw_all[$name] = Id3Reader::cleanTags($raw_tag);
        }

        $core = match ($this->type) {
            AudioTypeEnum::id3 => AudioCore::fromId3($tags->id3v1, $tags->id3v2),
            AudioTypeEnum::vorbiscomment => AudioCore::fromVorbisComment($tags->vorbiscomment),
            AudioTypeEnum::quicktime => AudioCore::fromQuicktime($tags->quicktime),
            AudioTypeEnum::matroska => AudioCore::fromMatroska($tags->matroska),
            AudioTypeEnum::ape => AudioCore::fromApe($tags->ape),
            AudioTypeEnum::asf => AudioCore::fromAsf($tags->asf),
            default => null,
        };

        if (! $core) {
            return $this;
        }

        $this->convertCore($core);
        $this->is_valid = true;
        $this->cover = AudioCover::make($id3_reader->getComments());

        if ($this->cover?->getContents()) {
            $this->has_cover = true;
        }

        return $this;
    }

    private function convertCore(?AudioCore $core): self
    {
        if (! $core) {
            return $this;
        }

        $this->title = $core->title;
        $this->artist = $core->artist;
        $this->album = $core->album;
        $this->genre = $core->genre;
        $this->year = $core->year;
        $this->track_number = $core->track_number;
        $this->comment = $core->comment;
        $this->album_artist = $core->album_artist;
        $this->composer = $core->composer;
        $this->disc_number = $core->disc_number;
        $this->is_compilation = $core->is_compilation;
        $this->creation_date = $core->creation_date;
        $this->encoding_by = $core->encoding_by;
        $this->encoding = $core->encoding;
        $this->copyright = $core->copyright;
        $this->description = $core->description;
        $this->synopsis = $core->synopsis;
        $this->language = $core->language;
        $this->lyrics = $core->lyrics;

        return $this;
    }
}
