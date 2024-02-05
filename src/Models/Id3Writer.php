<?php

namespace Kiwilan\Audio\Models;

use DateTime;
use getid3_writetags;
use Kiwilan\Audio\Audio;
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
     * @var array<string, array>
     */
    protected array $tags = [];

    /**
     * @var string[]
     */
    protected array $warnings = [];

    /**
     * @var string[]
     */
    protected array $errors = [];

    protected bool $overrideTags = true;

    protected bool $removeOldTags = false;

    protected bool $failOnError = true;

    /**
     * @var string[]
     */
    protected array $tagFormats = [];

    protected ?string $path = null;

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

    public function getCore(): AudioCore
    {
        return $this->core;
    }

    public function title(?string $title): self
    {
        $this->core->setTitle($title);

        return $this;
    }

    public function artist(?string $artist): self
    {
        $this->core->setArtist($artist);

        return $this;
    }

    public function album(?string $album): self
    {
        $this->core->setAlbum($album);

        return $this;
    }

    public function genre(?string $genre): self
    {
        $this->core->setGenre($genre);

        return $this;
    }

    public function year(int $year): self
    {
        $this->core->setYear($year);

        return $this;
    }

    public function trackNumber(string|int|null $trackNumber): self
    {
        if (is_int($trackNumber)) {
            $trackNumber = (string) $trackNumber;
        }

        $this->core->setTrackNumber($trackNumber);

        return $this;
    }

    public function comment(?string $comment): self
    {
        $this->core->setComment($comment);

        return $this;
    }

    public function albumArtist(?string $albumArtist): self
    {
        $this->core->setAlbumArtist($albumArtist);

        return $this;
    }

    public function composer(?string $composer): self
    {
        $this->core->setComposer($composer);

        return $this;
    }

    public function discNumber(string|int|null $discNumber): self
    {
        if (is_int($discNumber)) {
            $discNumber = (string) $discNumber;
        }

        $this->core->setDiscNumber($discNumber);

        return $this;
    }

    public function isCompilation(): self
    {
        $this->core->setIsCompilation(true);

        return $this;
    }

    public function isNotCompilation(): self
    {
        $this->core->setIsCompilation(false);

        return $this;
    }

    public function creationDate(string|DateTime|null $creationDate): self
    {
        if ($creationDate instanceof DateTime) {
            $creationDate = $creationDate->format('Y-m-d');
        }

        $this->core->setCreationDate($creationDate);

        return $this;
    }

    public function copyright(?string $copyright): self
    {
        $this->core->setCopyright($copyright);

        return $this;
    }

    public function encodingBy(?string $encodingBy): self
    {
        $this->core->setEncodingBy($encodingBy);

        return $this;
    }

    public function encoding(?string $encoding): self
    {
        $this->core->setEncoding($encoding);

        return $this;
    }

    public function description(?string $description): self
    {
        $this->core->setDescription($description);

        return $this;
    }

    public function podcastDescription(?string $podcastDescription): self
    {
        $this->core->setPodcastDescription($podcastDescription);

        return $this;
    }

    public function language(?string $language): self
    {
        $this->core->setLanguage($language);

        return $this;
    }

    public function lyrics(?string $lyrics): self
    {
        $this->core->setLyrics($lyrics);

        return $this;
    }

    public function stik(?string $stik): self
    {
        $this->core->setStik($stik);

        return $this;
    }

    /**
     * @param  string  $pathOrData  Path to cover image or binary data
     */
    public function cover(string $pathOrData): self
    {
        $this->core->setCover($pathOrData);

        return $this;
    }

    public function options(array $options = ['encoding' => 'UTF-8']): self
    {
        $this->options = $options;

        return $this;
    }

    public function path(string $path): self
    {
        $this->path = $path;

        if (file_exists($this->audio->getPath())) {
            copy($this->audio->getPath(), $this->path);
        }

        return $this;
    }

    /**
     * Prevent fail on error.
     */
    public function preventFailOnError(): self
    {
        $this->failOnError = false;

        return $this;
    }

    /**
     * Override existing tags, default is true.
     */
    // public function notOverrideTags(): self
    // {
    //     $this->overrideTags = false;

    //     return $this;
    // }

    /**
     * Remove other tags, default is false.
     */
    public function removeOldTags(): self
    {
        $this->removeOldTags = true;

        return $this;
    }

    /**
     * Set manually tags.
     *
     * @param  array<string, string>  $tags
     */
    public function tags(array $tags): self
    {
        $this->tags = $this->convertTags($tags);

        return $this;
    }

    /**
     * Set tag format.
     *
     * @param  string[]  $tags  Options are `id3v1`, `id3v2.2`, `id2v2.3`, `id3v2.4`, `ape`, `vorbiscomment`, `metaflac`, `real`
     */
    public function tagFormats(array $tags): self
    {
        $this->tagFormats = $tags;

        return $this;
    }

    /**
     * Save tags.
     *
     * @throws \Exception
     */
    public function save(): bool
    {
        if (! $this->path) {
            $this->path = $this->audio->getPath();
        }

        $this->instance->filename = $this->path;

        $this->convertTagFormats();
        $this->automaticConvert();

        $this->instance->overwrite_tags = $this->overrideTags;
        $this->instance->remove_other_tags = $this->removeOldTags;
        $this->instance->tagformats = $this->tagFormats;
        $this->instance->tag_data = $this->tags;

        $this->success = $this->instance->WriteTags();

        $this->errors = $this->instance->errors;
        $this->warnings = $this->instance->warnings;

        $errors = implode(', ', $this->errors);
        $warnings = implode(', ', $this->warnings);
        $supported = match ($this->audio->getFormat()) {
            AudioFormatEnum::flac => true,
            AudioFormatEnum::mp3 => true,
            AudioFormatEnum::ogg => true,
            default => false
        };

        if (! empty($this->errors)) {
            $msg = 'Save tags failed.';

            $errors = strip_tags($errors);
            $errors = "Errors: {$errors}.";
            if (! empty($this->errors)) {
                $msg .= " {$errors}";
            }

            $warnings = "Warnings: {$warnings}.";
            if (! empty($this->warnings)) {
                $msg .= " {$warnings}";
            }

            $isSuccess = $this->success ? 'true' : 'false';
            $success = "Success: {$isSuccess}";
            $msg .= " {$success}";

            error_log($msg);

            if ($this->failOnError) {
                throw new \Exception($msg);
            }
        }

        if (! $supported && $this->failOnError) {
            throw new \Exception("Format {$this->audio->getFormat()?->value} is not supported.");
        }

        if (! empty($this->warnings)) {
            error_log($warnings);
        }

        return $this->success;
    }

    private function automaticConvert(): self
    {
        $this->convertTagFormats();

        $convert = match ($this->audio->getType()) {
            AudioTypeEnum::id3 => AudioCore::toId3v2($this->core),
            AudioTypeEnum::vorbiscomment => AudioCore::toVorbisComment($this->core),
            AudioTypeEnum::quicktime => AudioCore::toQuicktime($this->core),
            AudioTypeEnum::matroska => AudioCore::toMatroska($this->core),
            AudioTypeEnum::ape => AudioCore::toApe($this->core),
            AudioTypeEnum::asf => AudioCore::toAsf($this->core),
            default => null,
        };

        $tags = [];
        if ($convert) {
            $tags = $convert->toArray();
        }

        $tags = $this->convertTags($tags);
        $this->attachCover($tags);

        $this->tags = [
            ...$this->tags,
            ...$tags,
        ];

        return $this;
    }

    /**
     * @param  array<string, string>  $tags
     * @return array<string, array>
     */
    private function convertTags(array $tags): array
    {
        $attached = $tags['attached_picture'] ?? null;
        $items = [];
        if (! empty($tags)) {
            foreach ($tags as $key => $tag) {
                if ($tag && gettype($tag) === 'string') {
                    $items[$key] = [$tag];
                }
            }
        }

        if ($attached) {
            $items['attached_picture'] = $attached;
        }

        return $items;
    }

    /**
     * - ID3v1 (v1 & v1.1)
     * - ID3v2 (v2.3, v2.4)
     * - APE (v2)
     * - Ogg Vorbis comments (need `vorbis-tools`)
     * - FLAC comments
     *
     * Options: `id3v1`, `id3v2.2`, `id2v2.3`, `id3v2.4`, `ape`, `vorbiscomment`, `metaflac`, `real`
     */
    private function convertTagFormats(): self
    {
        if (! empty($this->tagFormats)) {
            return $this;
        }

        $formats = match ($this->audio->getFormat()) {
            AudioFormatEnum::aac => [],
            AudioFormatEnum::aif => [],
            AudioFormatEnum::aifc => [],
            AudioFormatEnum::aiff => [],
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
            AudioFormatEnum::opus => [],
            AudioFormatEnum::ofr => [],
            AudioFormatEnum::ofs => [],
            AudioFormatEnum::spx => [],
            AudioFormatEnum::tak => [],
            AudioFormatEnum::tta => [],
            AudioFormatEnum::wav => [],
            AudioFormatEnum::webm => [],
            AudioFormatEnum::wma => [],
            AudioFormatEnum::wv => [],
            default => null,
        };
        $this->tagFormats = $formats;

        return $this;
    }

    private function attachCover(array &$tags): void
    {
        $coverFormatsAllowed = [AudioFormatEnum::mp3];
        if ($this->core->getCover() && in_array($this->audio->getFormat(), $coverFormatsAllowed)) {
            // $tags = [
            //     ...$tags,
            //     'CTOC' => $old_tags['id3v2']['CTOC'],
            //     'CHAP' => $old_tags['id3v2']['CHAP'],
            //     'chapters' => $old_tags['id3v2']['chapters'],
            // ];
            $tags['attached_picture'][0] = [
                'data' => base64_decode($this->core->getCover()->data()),
                'picturetypeid' => $this->core->getCover()->picturetypeid(),
                'description' => $this->core->getCover()->description(),
                'mime' => $this->core->getCover()->mime(),
            ];
            $this->core->setHasCover(true);
        }
    }
}
