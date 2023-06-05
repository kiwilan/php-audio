<?php

namespace Kiwilan\Audio\Models;

use DateTime;
use getid3_writetags;
use Kiwilan\Audio\Audio;
use Kiwilan\Audio\AudioConverter;
use Kiwilan\Audio\Enums\AudioFormatEnum;
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

    protected array $warnings = [];

    protected array $errors = [];

    protected bool $overrideTags = true;

    protected bool $removeOldTags = false;

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

    public function core(): AudioCore
    {
        return $this->core;
    }

    public function setTitle(?string $title): self
    {
        $this->core->setTitle($title);

        return $this;
    }

    public function setArtist(?string $artist): self
    {
        $this->core->setArtist($artist);

        return $this;
    }

    public function setAlbum(?string $album): self
    {
        $this->core->setAlbum($album);

        return $this;
    }

    public function setGenre(?string $genre): self
    {
        $this->core->setGenre($genre);

        return $this;
    }

    public function setYear(int $year): self
    {
        $this->core->setYear($year);

        return $this;
    }

    public function setTrackNumber(?string $trackNumber): self
    {
        $this->core->setTrackNumber($trackNumber);

        return $this;
    }

    public function setComment(?string $comment): self
    {
        $this->core->setComment($comment);

        return $this;
    }

    public function setAlbumArtist(?string $albumArtist): self
    {
        $this->core->setAlbumArtist($albumArtist);

        return $this;
    }

    public function setComposer(?string $composer): self
    {
        $this->core->setComposer($composer);

        return $this;
    }

    public function setDiscNumber(?string $discNumber): self
    {
        $this->core->setDiscNumber($discNumber);

        return $this;
    }

    public function setIsCompilation(bool $isCompilation): self
    {
        $this->core->setIsCompilation($isCompilation);

        return $this;
    }

    public function setCreationDate(string|DateTime|null $creationDate): self
    {
        if ($creationDate instanceof DateTime) {
            $creationDate = $creationDate->format('Y-m-d');
        }

        $this->core->setCreationDate($creationDate);

        return $this;
    }

    public function setCopyright(?string $copyright): self
    {
        $this->core->setCopyright($copyright);

        return $this;
    }

    public function setEncodingBy(?string $encodingBy): self
    {
        $this->core->setEncodingBy($encodingBy);

        return $this;
    }

    public function setEncoding(?string $encoding): self
    {
        $this->core->setEncoding($encoding);

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->core->setDescription($description);

        return $this;
    }

    public function setLyrics(?string $lyrics): self
    {
        $this->core->setLyrics($lyrics);

        return $this;
    }

    public function setStik(?string $stik): self
    {
        $this->core->setStik($stik);

        return $this;
    }

    /**
     * @param  string  $pathOrData Path to cover image or binary data
     */
    public function setCover(string $pathOrData): self
    {
        $this->core->setCover($pathOrData);

        return $this;
    }

    public function options(array $options = ['encoding' => 'UTF-8']): self
    {
        $this->options = $options;

        return $this;
    }

    public function write(): self
    {
        $this->instance->filename = $this->audio->path();

        return $this;
    }

    /**
     * Override existing tags, default is true.
     */
    public function overrideTags(bool $value): self
    {
        $this->instance->overwrite_tags = $value;

        return $this;
    }

    /**
     * Remove other tags, default is false.
     */
    public function removeOldTags(bool $value): self
    {
        $this->instance->remove_other_tags = $value;

        return $this;
    }

    public function save(): bool
    {
        /**
         * - ID3v1 (v1 & v1.1)
         * - ID3v2 (v2.3, v2.4)
         * - APE (v2)
         * - Ogg Vorbis comments (need `vorbis-tools`)
         * - FLAC comments
         *
         * Options: `id3v1`, `id3v2.2`, `id2v2.3`, `id3v2.4`, `ape`, `vorbiscomment`, `metaflac`, `real`
         */
        $format = match ($this->audio->format()) {
            AudioFormatEnum::aac => [],
            AudioFormatEnum::aif => ['id3v2.4'],
            AudioFormatEnum::aifc => ['id3v2.4'],
            AudioFormatEnum::aiff => ['id3v2.4'],
            AudioFormatEnum::dsf => [],
            AudioFormatEnum::flac => ['metaflac'],
            AudioFormatEnum::m4a => [],
            AudioFormatEnum::m4b => [],
            AudioFormatEnum::m4v => [],
            AudioFormatEnum::mpc => [],
            AudioFormatEnum::mka => [],
            AudioFormatEnum::mkv => [],
            AudioFormatEnum::ape => [],
            AudioFormatEnum::mp3 => ['id3v1', 'id3v2.4'],
            AudioFormatEnum::mp4 => [],
            AudioFormatEnum::ogg => ['vorbiscomment'],
            AudioFormatEnum::opus => ['vorbiscomment'],
            AudioFormatEnum::ofr => [],
            AudioFormatEnum::ofs => [],
            AudioFormatEnum::spx => ['vorbiscomment'],
            AudioFormatEnum::tak => [],
            AudioFormatEnum::tta => ['ape'],
            AudioFormatEnum::wav => ['id3v2.4'],
            AudioFormatEnum::webm => [],
            AudioFormatEnum::wma => [],
            AudioFormatEnum::wv => ['ape'],
            default => null,
        };
        $this->instance->tagformats = $format;

        $convert = match ($this->audio->type()) {
            AudioTypeEnum::id3 => AudioConverter::toId3v2($this->core),
            AudioTypeEnum::vorbiscomment => AudioConverter::toVorbisComment($this->core),
            AudioTypeEnum::quicktime => AudioConverter::toQuicktime($this->core),
            AudioTypeEnum::matroska => AudioConverter::toMatroska($this->core),
            AudioTypeEnum::ape => AudioConverter::toApe($this->core),
            AudioTypeEnum::asf => AudioConverter::toAsf($this->core),
            default => null,
        };

        if ($convert) {
            $this->tags = $convert->toArray();
        }

        $this->instance->overwrite_tags = $this->overrideTags;
        $this->instance->remove_other_tags = $this->removeOldTags;

        $tags = [];
        if (! empty($this->tags)) {
            foreach ($this->tags as $key => $tag) {
                if ($tag) {
                    $tags[$key] = [$tag];
                }
            }
        }

        $coverFormatsAllowed = [AudioFormatEnum::mp3];
        if ($this->core->cover() && in_array($this->audio->format(), $coverFormatsAllowed)) {
            // $tags = [
            //     ...$tags,
            //     'CTOC' => $old_tags['id3v2']['CTOC'],
            //     'CHAP' => $old_tags['id3v2']['CHAP'],
            //     'chapters' => $old_tags['id3v2']['chapters'],
            // ];
            $tags['attached_picture'][0] = [
                'data' => base64_decode($this->core->cover()->data()),
                'picturetypeid' => $this->core->cover()->picturetypeid(),
                'description' => $this->core->cover()->description(),
                'mime' => $this->core->cover()->mime(),
            ];
            $this->core->setHasCover(true);
        }

        $this->instance->tag_data = $tags;
        $this->success = $this->instance->WriteTags();

        $this->errors = $this->instance->errors;
        $this->warnings = $this->instance->warnings;

        if (! empty($this->errors)) {
            $errors = implode(', ', $this->errors);
            throw new \Exception($errors);
        }

        if (! empty($this->warnings)) {
            $warnings = implode(', ', $this->warnings);
            error_log($warnings);
        }

        return $this->success;
    }
}
