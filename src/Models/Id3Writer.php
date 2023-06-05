<?php

namespace Kiwilan\Audio\Models;

use DateTime;
use getid3_writetags;
use Kiwilan\Audio\Audio;
use Kiwilan\Audio\AudioConverter;
use Kiwilan\Audio\Enums\AudioTypeEnum;

class Id3Writer
{
    /**
     * @var array<string, string>
     */
    protected array $options = [
        'encoding' => 'UTF-8',
    ];

    /**
     * @var array<string, string>
     */
    protected array $tags = [];

    protected bool $success = false;

    protected function __construct(
        protected Audio $audio,
        protected getid3_writetags $instance,
        protected AudioCore $core,
    ) {
    }

    public static function make(Audio $audio): self
    {
        $self = new self(
            audio: $audio,
            instance: new getid3_writetags(),
            core: new AudioCore()
        );

        return $self;
    }

    public function write(): self
    {
        $this->instance->filename = $this->audio->path();
        $this->instance->tagformats = ['id3v2.3'];

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->core->setTitle($title);

        return $this;
    }

    public function setArtist(string $artist): self
    {
        $this->core->setArtist($artist);

        return $this;
    }

    public function setAlbum(string $album): self
    {
        $this->core->setAlbum($album);

        return $this;
    }

    public function setGenre(string $genre): self
    {
        $this->core->setGenre($genre);

        return $this;
    }

    public function setYear(int $year): self
    {
        $this->core->setYear($year);

        return $this;
    }

    public function setTrackNumber(string $trackNumber): self
    {
        $this->core->setTrackNumber($trackNumber);

        return $this;
    }

    public function setComment(string $comment): self
    {
        $this->core->setComment($comment);

        return $this;
    }

    public function setAlbumArtist(string $albumArtist): self
    {
        $this->core->setAlbumArtist($albumArtist);

        return $this;
    }

    public function setComposer(string $composer): self
    {
        $this->core->setComposer($composer);

        return $this;
    }

    public function setDiscNumber(string $discNumber): self
    {
        $this->core->setDiscNumber($discNumber);

        return $this;
    }

    public function setIsCompilation(bool $isCompilation): self
    {
        $this->core->setIsCompilation($isCompilation);

        return $this;
    }

    public function setCreationDate(string|DateTime $creationDate): self
    {
        if ($creationDate instanceof DateTime) {
            $creationDate = $creationDate->format('Y-m-d');
        }

        $this->core->setCreationDate($creationDate);

        return $this;
    }

    public function setCopyright(string $copyright): self
    {
        $this->core->setCopyright($copyright);

        return $this;
    }

    public function setEncoding(string $encoding): self
    {
        $this->core->setEncoding($encoding);

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->core->setDescription($description);

        return $this;
    }

    public function setLyrics(string $lyrics): self
    {
        $this->core->setLyrics($lyrics);

        return $this;
    }

    public function setStik(string $stik): self
    {
        $this->core->setStik($stik);

        return $this;
    }

    public function options(array $options = ['encoding' => 'UTF-8']): self
    {
        $this->options = $options;

        return $this;
    }

    public function save(bool $override = true, bool $removeOldTags = false): bool
    {
        $tags = $this->core->toArray();
        $this->tags = $tags;

        $convert = match ($this->audio->format()) {
            AudioTypeEnum::id3 => AudioConverter::toId3v2($this->core),
            AudioTypeEnum::vorbiscomment => AudioConverter::toVorbisComment($this->core),
            AudioTypeEnum::quicktime => AudioConverter::toQuicktime($this->core),
            AudioTypeEnum::matroska => AudioConverter::toMatroska($this->core),
            AudioTypeEnum::ape => AudioConverter::toApe($this->core),
            AudioTypeEnum::asf => AudioConverter::toAsf($this->core),
            default => null,
        };
        ray($convert);

        $this->instance->overwrite_tags = $override;
        $this->instance->remove_other_tags = $removeOldTags;

        $tags = [];
        if (! empty($this->tags)) {
            foreach ($this->tags as $key => $tag) {
                $tags[$key] = [$tag];
            }
        }

        $this->instance->tag_data = $tags;
        $this->success = $this->instance->WriteTags();

        // ray($this);

        return $this->success;
    }
}
