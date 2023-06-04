<?php

namespace Kiwilan\Audio;

use Kiwilan\Audio\Models\AudioCover;
use Kiwilan\Audio\Models\AudioMetadata;
use Kiwilan\Audio\Models\FileStat;
use Kiwilan\Audio\Models\Id3AudioTag;

class Audio
{
    protected ?string $type = null;

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

    protected ?string $encoding = null;

    protected ?string $description = null;

    protected ?string $lyrics = null;

    protected ?string $stik = null;

    protected ?float $duration = null;

    protected bool $isValid = false;

    protected array $extras = [];

    protected ?AudioMetadata $audio = null;

    protected bool $hasCover = false;

    protected ?AudioCover $cover = null;

    protected function __construct(
        protected string $path,
        protected string $extension,
        protected Id3 $id3,
        protected FileStat $stat,
    ) {
    }

    public static function read(string $path): self
    {
        $self = new self(
            path: $path,
            extension: pathinfo($path, PATHINFO_EXTENSION),
            id3: Id3::make($path),
            stat: FileStat::make($path)
        );
        $self->audio = AudioMetadata::make($self->id3->item());
        $self->parse();

        return $self;
    }

    public function id3(): Id3
    {
        return $this->id3;
    }

    public function stat(): ?FileStat
    {
        return $this->stat;
    }

    public function title(): ?string
    {
        return $this->title;
    }

    public function artist(): ?string
    {
        return $this->artist;
    }

    public function album(): ?string
    {
        return $this->album;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function year(): ?int
    {
        return $this->year;
    }

    public function trackNumber(): ?string
    {
        return $this->trackNumber;
    }

    public function comment(): ?string
    {
        return $this->comment;
    }

    public function albumArtist(): ?string
    {
        return $this->albumArtist;
    }

    public function composer(): ?string
    {
        return $this->composer;
    }

    public function discNumber(): ?string
    {
        return $this->discNumber;
    }

    public function isCompilation(): bool
    {
        return $this->isCompilation;
    }

    public function creationDate(): ?string
    {
        return $this->creationDate;
    }

    public function encoding(): ?string
    {
        return $this->encoding;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function lyrics(): ?string
    {
        return $this->lyrics;
    }

    public function stik(): ?string
    {
        return $this->stik;
    }

    public function duration(): ?float
    {
        return $this->duration;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function extras(): array
    {
        return $this->extras;
    }

    public function audio(): ?AudioMetadata
    {
        return $this->audio;
    }

    public function hasCover(): bool
    {
        return $this->hasCover;
    }

    public function cover(): ?AudioCover
    {
        return $this->cover;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function extension(): string
    {
        return $this->extension;
    }

    private function parse(): self
    {
        $raw = $this->id3->raw();
        $item = $this->id3->item();

        $this->type = match ($this->extension) {
            'aac' => null,
            'flac' => 'vorbiscomment',
            'm4a' => 'quicktime',
            'm4b' => 'quicktime',
            'mp3' => 'id3',
            'mp4' => 'quicktime',
            'wav' => 'id3',
            'wma' => 'asf',
            default => null,
        };

        $tags = $item->tags();
        if (! $tags) {
            return $this;
        }

        if ($this->type === 'id3') {
            $v1 = $tags->id3v1();
            $v2 = $tags->id3v2();

            $year = $v2?->year() ?? $v1?->year();
            $this->title = $v2?->title() ?? $v1?->title();
            $this->artist = $v2?->artist() ?? $v1?->artist();
            $this->album = $v2?->album() ?? $v1?->album();
            $this->genre = $v2?->genre() ?? $v1?->genre();
            $this->year = $year ? (int) $year : null;
            $this->trackNumber = $v2?->track_number() ?? $v1?->track_number();
            $this->comment = $v2?->comment() ?? $v1?->comment();
            $this->albumArtist = $v2?->band() ?? null;
            $this->composer = $v2?->composer() ?? null;
            $this->discNumber = $v2?->part_of_a_set() ?? null;
            $this->isCompilation = $v2?->part_of_a_compilation() ?? false;
            $this->isValid = true;
        }

        if ($this->type === 'quicktime') {
            $this->parseQuicktime($tags);
            $this->isValid = true;
        }

        if ($this->type === 'vorbiscomment') {
            $vorbis = $tags->vorbiscomment();

            $this->title = $vorbis->title();
            $this->artist = $vorbis->artist();
            $this->album = $vorbis->album();
            $this->genre = $vorbis->genre();
            $this->trackNumber = $vorbis->tracknumber();
            $this->comment = $vorbis->comment();
            $this->albumArtist = $vorbis->albumartist();
            $this->composer = $vorbis->composer();
            $this->discNumber = $vorbis->discnumber();
            $this->isCompilation = $vorbis->compilation();
            $this->year = (int) $vorbis->date();
            $this->encoding = $vorbis->encoder();
            $this->comment = $vorbis->description();
            $this->isValid = true;
        }

        if ($this->type === 'asf') {
            $asf = $tags->asf();

            $this->title = $asf->title();
            $this->artist = $asf->artist();
            $this->album = $asf->album();
            $this->albumArtist = $asf->albumartist();
            $this->composer = $asf->composer();
            $this->discNumber = $asf->partofset();
            $this->genre = $asf->genre();
            $this->trackNumber = $asf->track_number();
            $this->year = (int) $asf->year();
            $this->encoding = $asf->encodingsettings();
            $this->isValid = true;
        }

        $this->extras = $raw['tags'] ?? [];

        $this->audio = AudioMetadata::make($item);
        $this->cover = AudioCover::make($item->comments());

        if ($this->cover?->content()) {
            $this->hasCover = true;
        }

        $this->duration = number_format((float) $this->audio->durationSeconds(), 2, '.', '');

        return $this;
    }

    private function parseQuicktime(Id3AudioTag $tags): self
    {
        $quicktime = $tags->quicktime();

        $creation_date = $quicktime->creation_date();
        $description = $quicktime->description();
        $description_long = $quicktime->description_long();
        $encoded_by = $quicktime->encoded_by();

        if ($description_long && $description && strlen($description_long) > strlen($description)) {
            $description = $description_long;
        }

        $this->title = $quicktime->title();
        $this->artist = $quicktime->artist();
        $this->album = $quicktime->album();
        $this->genre = $quicktime->genre();
        $this->trackNumber = $quicktime->track_number();
        $this->discNumber = $quicktime->disc_number();
        $this->composer = $quicktime->composer();
        $this->isCompilation = $quicktime->compilation();
        $this->comment = $quicktime->comment();
        $this->albumArtist = $quicktime->album_artist();
        $this->encoding = $quicktime->encoding_tool();
        if ($encoded_by) {
            $this->encoding = "{$this->encoding} ($encoded_by)";
        }

        if ($creation_date) {
            if (strlen($creation_date) === 4) {
                $this->year = (int) $creation_date;
            } else {
                $creation_date = date_create_from_format('Y-m-d\TH:i:s\Z', $creation_date);
                $this->creationDate = $creation_date?->format('Y-m-d\TH:i:s\Z');
                $this->year = (int) $creation_date?->format('Y');
            }
        }

        $this->copyright = $quicktime->copyright();
        $this->description = $description;
        $this->lyrics = $quicktime->lyrics();
        $this->stik = $quicktime->stik();

        return $this;
    }
}
