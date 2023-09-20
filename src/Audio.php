<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;
use Kiwilan\Audio\Models\AudioCore;
use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;
use Kiwilan\Audio\Models\AudioStat;
use Kiwilan\Audio\Models\Id3Reader;
use Kiwilan\Audio\Models\Id3Writer;

class Audio
{
    protected ?string $title = null;

    protected ?string $artist = null;

    protected ?string $album = null;

    protected ?string $genre = null;

    protected ?int $year = null;

    protected ?string $trackNumber = null;

    protected ?string $comment = null;

    protected ?string $albumArtist = null;

    protected ?string $composer = null;

    protected ?string $discNumber = null;

    protected bool $isCompilation = false;

    protected ?string $creationDate = null;

    protected ?string $copyright = null;

    protected ?string $encodingBy = null;

    protected ?string $encoding = null;

    protected ?string $description = null;

    protected ?string $lyrics = null;

    protected ?string $stik = null;

    protected ?float $duration = null;

    protected array $extras = [];

    protected ?AudioMetadata $audio = null;

    protected bool $hasCover = false;

    protected ?AudioCover $cover = null;

    protected bool $isValid = false;

    protected ?AudioTypeEnum $type = null;

    protected function __construct(
        protected string $path,
        protected string $extension,
        protected AudioFormatEnum $format,
        protected AudioStat $stat,
        protected Id3Reader $reader,
        protected ?Id3Writer $writer = null,
    ) {
    }

    public static function get(string $path): self
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
            stat: AudioStat::make($path),
            reader: Id3Reader::make($path),
        );
        if ($self->reader->isWritable()) {
            $self->writer = Id3Writer::make($self);
        }
        $self->audio = AudioMetadata::make($self);
        $self->parse();

        return $self;
    }

    /**
     * Update metadata of audio file.
     */
    public function update(): Id3Writer
    {
        return $this->writer;
    }

    /**
     * Get the value of `stat` method.
     */
    public function getStat(): ?AudioStat
    {
        return $this->stat;
    }

    /**
     * `Id3Reader` with metadata.
     */
    public function getReader(): Id3Reader
    {
        return $this->reader;
    }

    /**
     * `Id3Writer` to update metadata.
     */
    public function getWriter(): ?Id3Writer
    {
        return $this->writer;
    }

    /**
     * Get `title` metadata.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Get `artist` metadata.
     */
    public function getArtist(): ?string
    {
        return $this->artist;
    }

    /**
     * Get `album` metadata.
     */
    public function getAlbum(): ?string
    {
        return $this->album;
    }

    /**
     * Get `genre` metadata.
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Get `year` metadata.
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * Get `trackNumber` metadata.
     */
    public function getTrackNumber(): ?string
    {
        return $this->trackNumber;
    }

    /**
     * Get `comment` metadata.
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Get `albumArtist` metadata.
     */
    public function getAlbumArtist(): ?string
    {
        return $this->albumArtist;
    }

    /**
     * Get `composer` metadata.
     */
    public function getComposer(): ?string
    {
        return $this->composer;
    }

    /**
     * Get `discNumber` metadata.
     */
    public function getDiscNumber(): ?string
    {
        return $this->discNumber;
    }

    /**
     * Know if audio file is a compilation.
     */
    public function isCompilation(): bool
    {
        return $this->isCompilation;
    }

    /**
     * Get `creationDate` metadata for audiobook.
     */
    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    /**
     * Get `encodingBy` metadata for audiobook.
     */
    public function getEncodingBy(): ?string
    {
        return $this->encodingBy;
    }

    /**
     * Get `encoding` metadata for audiobook.
     */
    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    /**
     * Get `encoding` metadata for audiobook.
     */
    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    /**
     * Get `description` metadata for audiobook.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get `lyrics` metadata for audiobook.
     */
    public function getLyrics(): ?string
    {
        return $this->lyrics;
    }

    /**
     * Get `stik` metadata for audiobook.
     */
    public function getStik(): ?string
    {
        return $this->stik;
    }

    /**
     * Get `duration` in seconds.
     */
    public function getDuration(): ?float
    {
        return $this->duration;
    }

    /**
     * Know if audio file is valid.
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * Get `extras` with raw metadata.
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Get `audio` metadata with some audio information.
     */
    public function getAudio(): ?AudioMetadata
    {
        return $this->audio;
    }

    /**
     * Know if audio file has cover.
     */
    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    /**
     * Get `cover` metadata with some cover information.
     */
    public function getCover(): ?AudioCover
    {
        return $this->cover;
    }

    /**
     * Get `path` of audio file.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get `extension` of audio file.
     */
    public function getgetExtension(): string
    {
        return $this->extension;
    }

    /**
     * Get `format` of audio file.
     */
    public function getFormat(): AudioFormatEnum
    {
        return $this->format;
    }

    /**
     * Get `type` of audio file.
     */
    public function getType(): ?AudioTypeEnum
    {
        return $this->type;
    }

    private function parse(): self
    {
        $raw = $this->getReader()->getRaw();
        $reader = $this->getReader();

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

        $tags = $reader->getTags();
        if (! $tags) {
            return $this;
        }

        $core = null;
        if ($this->type === AudioTypeEnum::id3) {
            $core = AudioCore::fromId3($tags->id3v1(), $tags->id3v2());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::quicktime) {
            $core = AudioCore::fromQuicktime($tags->quicktime());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::vorbiscomment) {
            $core = AudioCore::fromVorbisComment($tags->vorbiscomment());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::asf) {
            $core = AudioCore::fromAsf($tags->asf());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::matroska) {
            $core = AudioCore::fromMatroska($tags->matroska());
            $this->isValid = true;
        }

        if ($this->type === AudioTypeEnum::ape) {
            $core = AudioCore::fromApe($tags->ape());
            $this->isValid = true;
        }

        $this->coreToProperties($core);
        $this->extras = $raw['tags'] ?? [];

        $this->audio = AudioMetadata::make($this);
        $this->cover = AudioCover::make($reader->getComments());

        if ($this->cover?->getContents()) {
            $this->hasCover = true;
        }

        $this->duration = number_format((float) $this->audio->getDurationSeconds(), 2, '.', '');

        return $this;
    }

    private function coreToProperties(?AudioCore $core): self
    {
        if (! $core) {
            return $this;
        }

        $this->title = $core->getTitle();
        $this->artist = $core->getArtist();
        $this->album = $core->getAlbum();
        $this->genre = $core->getGenre();
        $this->year = $core->getYear();
        $this->trackNumber = $core->getTrackNumber();
        $this->comment = $core->getComment();
        $this->albumArtist = $core->getAlbumArtist();
        $this->composer = $core->getComposer();
        $this->discNumber = $core->getDiscNumber();
        $this->isCompilation = $core->isCompilation();
        $this->creationDate = $core->getCreationDate();
        $this->encodingBy = $core->getEncodingBy();
        $this->encoding = $core->getEncoding();
        $this->copyright = $core->getCopyright();
        $this->description = $core->getDescription();
        $this->lyrics = $core->getLyrics();
        $this->stik = $core->getStik();

        return $this;
    }
}
