<?php

namespace Kiwilan\Audio\Id3;

use getid3_writetags;
use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Core\AudioCoreCover;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;

class Id3Writer
{
    protected const ALLOWED_COVER_TYPE = [AudioTypeEnum::id3];

    /**
     * @param  array<string, array>  $tags  Array for `Id3Writer` format.
     * @param  string[]  $tags_core  Tags from dedicated methods.
     * @param  string[]  $tags_custom  Tags from `tag()` method.
     * @param  string[]  $tags_custom_bulk  Tags from `tags()` method.
     * @param  string[]  $warnings
     * @param  string[]  $errors
     * @param  string[]  $formats  Formats to write tags.
     */
    protected function __construct(
        protected Audio $audio,
        protected getid3_writetags $writer,
        protected AudioCore $core,
        protected array $tags = [],
        protected array $tags_core = [],
        protected array $tags_current = [],
        protected array $tags_custom = [],
        protected array $tags_custom_bulk = [],
        protected array $warnings = [],
        protected array $errors = [],
        protected bool $cover_deleted = false,
        protected bool $skip_errors = false,
        protected array $formats = [],
        protected bool $success = false,
    ) {}

    public static function make(Audio $audio): self
    {
        $self = new self(
            audio: $audio,
            writer: new getid3_writetags,
            core: new AudioCore,
        );

        $self->writer->filename = $audio->getPath();

        return $self;
    }

    public function getCore(): AudioCore
    {
        return $this->core;
    }

    /**
     * Allow to remove other tags when writing tags.
     */
    public function removeOtherTags(): self
    {
        $this->writer->remove_other_tags = true;

        return $this;
    }

    public function title(?string $title): self
    {
        $this->core->title = $title;

        return $this;
    }

    public function artist(?string $artist): self
    {
        $this->core->artist = $artist;

        return $this;
    }

    public function album(?string $album): self
    {
        $this->core->album = $album;

        return $this;
    }

    public function year(string|int|null $year): self
    {
        if (! $year) {
            $this->core->year = null;

            return $this;
        }

        $this->core->year = intval($year);

        return $this;
    }

    public function genre(?string $genre): self
    {
        $this->core->genre = $genre;

        return $this;
    }

    public function trackNumber(string|int|null $track_number): self
    {
        if (! $track_number) {
            $this->core->track_number = null;

            return $this;
        }

        if (is_int($track_number)) {
            $track_number = (string) $track_number;
        }

        $this->core->track_number = $track_number;

        return $this;
    }

    public function discNumber(string|int|null $disc_number): self
    {
        if (! $disc_number) {
            $this->core->disc_number = null;

            return $this;
        }

        if (is_int($disc_number)) {
            $disc_number = (string) $disc_number;
        }

        $this->core->disc_number = $disc_number;

        return $this;
    }

    public function composer(?string $composer): self
    {
        $this->core->composer = $composer;

        return $this;
    }

    public function comment(?string $comment): self
    {
        $this->core->comment = $comment;

        return $this;
    }

    public function lyrics(?string $lyrics): self
    {
        $this->core->lyrics = $lyrics;

        return $this;
    }

    public function isCompilation(): self
    {
        $this->core->is_compilation = true;

        return $this;
    }

    public function isNotCompilation(): self
    {
        $this->core->is_compilation = false;

        return $this;
    }

    /**
     * Not supported by `id3`.
     */
    public function creationDate(?string $creation_date): self
    {
        $this->core->creation_date = $creation_date;

        return $this;
    }

    public function copyright(?string $copyright): self
    {
        $this->core->copyright = $copyright;

        return $this;
    }

    /**
     * Not supported by `id3`.
     */
    public function encodingBy(?string $encoding_by): self
    {
        $this->core->encoding_by = $encoding_by;

        return $this;
    }

    /**
     * Not supported by `id3`.
     */
    public function encoding(?string $encoding): self
    {
        $this->core->encoding = $encoding;

        return $this;
    }

    /**
     * Not supported by `id3`.
     */
    public function description(?string $description): self
    {
        $this->core->description = $description;

        return $this;
    }

    /**
     * Not supported by `id3`.
     */
    public function synopsis(?string $synopsis): self
    {
        $this->core->synopsis = $synopsis;

        return $this;
    }

    public function language(?string $language): self
    {
        $this->core->language = $language;

        return $this;
    }

    /**
     * Set new album artist.
     */
    public function albumArtist(?string $album_artist): self
    {
        $this->core->album_artist = $album_artist;

        return $this;
    }

    /**
     * To create a copy of the audio file with new tags.
     */
    public function path(string $path): self
    {
        if (file_exists($path)) {
            unlink($path);
        }
        copy($this->audio->getPath(), $path);

        $this->writer->filename = $path;

        return $this;
    }

    /**
     * Advanced usage only to set tags formats.
     *
     * @param  string[]  $tag_formats
     */
    public function tagFormats(array $tag_formats): self
    {
        $this->formats = $tag_formats;

        return $this;
    }

    /**
     * Remove cover from tags.
     */
    public function removeCover(): self
    {
        $this->cover_deleted = true;

        return $this;
    }

    /**
     * Update cover is only supported by `id3` format.
     *
     * @param  string  $pathOrData  Path to cover image or binary data
     */
    public function cover(string $pathOrData): self
    {
        $this->core->cover = AudioCoreCover::make($pathOrData);
        $this->core->has_cover = true;

        return $this;
    }

    /**
     * Add custom tags without dedicated method (can be use multiple times).
     *
     * To know which key use for each format, see documentation.
     * For example, album artist for `id3` encoded files, is `band` key.
     *
     * @docs https://github.com/kiwilan/php-audio#convert-properties
     *
     * Example:
     *
     * ```php
     * $audio->write()
     *  ->tag('series-part', '1')
     *  ->tag('series', 'The Lord of the Rings');
     * ```
     */
    public function tag(string $key, string|int|bool|null $value): self
    {
        $this->tags_custom[$key] = $value;

        return $this;
    }

    /**
     * Alternative to `tag()` method, with a full array of tags.
     *
     * To know which key use for each format, see documentation.
     * For example, album artist for `id3` encoded files, is `band` key.
     *
     * @docs https://github.com/kiwilan/php-audio#convert-properties
     *
     * @param  array<string, string|int|bool|null>  $tags
     *
     * Example:
     *
     * ```php
     * $audio->write()
     *  ->tags([
     *      'series-part' => '1',
     *      'series' => 'The Lord of the Rings',
     *  ]);
     * ```
     */
    public function tags(array $tags): self
    {
        $this->tags_custom_bulk = $tags;

        return $this;
    }

    /**
     * Skip errors when writing tags.
     */
    public function skipErrors(): self
    {
        $this->skip_errors = true;

        return $this;
    }

    /**
     * Write new tags on file.
     */
    public function save(): bool
    {
        $this->assignFormats();
        $this->assignTagsCurrent();
        $this->assignCoverCurrent();
        $this->assignTagsCore();
        $this->assignTagsCustom();

        $this->convertToWriter();
        $this->convertCoverToWriter();

        $this->writer->tagformats = $this->formats;
        $this->writer->tag_data = $this->tags;

        $this->success = $this->writer->WriteTags();
        $this->errors = $this->writer->errors;
        $this->warnings = $this->writer->warnings;

        $this->handleErrors();

        return $this->success;
    }

    private function handleErrors(): void
    {
        $errors = implode(', ', $this->errors);
        $warnings = implode(', ', $this->warnings);
        $errors = strip_tags($errors);
        $warnings = strip_tags($warnings);

        $supported = match ($this->audio->getFormat()) {
            AudioFormatEnum::flac => true,
            AudioFormatEnum::mp3 => true,
            AudioFormatEnum::ogg => true,
            AudioFormatEnum::m4b => true,
            default => false
        };

        if (! $supported && ! $this->skip_errors) {
            throw new \Exception("php-audio: format {$this->audio->getFormat()->value} is not supported.");
        }

        if (! empty($this->errors)) {
            error_log("php-audio: {$errors}");
        }

        if (! empty($this->warnings)) {
            error_log("php-audio: {$warnings}");
        }

        if (empty($this->errors) && empty($this->warnings)) {
            return;
        }

        $msg = 'php-audio: Save tags failed.';
        if ($errors) {
            $msg .= " Errors: {$errors}.";
        }
        if ($warnings) {
            $msg .= " Warnings: {$warnings}.";
        }
        $isSuccess = $this->success ? 'true' : 'false';
        $msg .= " Success: {$isSuccess}.";
        error_log($msg);

        if (! $this->skip_errors) {
            throw new \Exception($msg);
        }
    }

    /**
     * Parse all tags to convert it to writer format.
     */
    private function convertToWriter(): self
    {
        $tags = [];

        // set current tags
        foreach ($this->tags_current as $key => $value) {
            $tags[$key] = $value;
        }

        // set custom tags
        foreach ($this->tags_custom as $key => $value) {
            $tags[$key] = $value;
        }

        // set custom bulk tags
        foreach ($this->tags_custom_bulk as $key => $value) {
            $tags[$key] = $value;
        }

        // set core tags
        foreach ($this->tags_core as $key => $value) {
            $tags[$key] = $value;
        }

        $this->tags = $this->formatTags($tags);

        $forbiddenKeys = ['totaltracks'];
        foreach ($forbiddenKeys as $key) {
            if (isset($this->tags[$key])) {
                unset($this->tags[$key]);
            }
        }

        return $this;
    }

    private function assignTagsCustom(): self
    {
        if (empty($this->tags_custom) || empty($this->tags_custom_bulk)) {
            return $this;
        }

        foreach ($this->tags_custom as $key => $value) {
            $this->tags_current[$key] = $value;
        }

        foreach ($this->tags_custom_bulk as $key => $value) {
            $this->tags_current[$key] = $value;
        }

        return $this;
    }

    /**
     * Assign current cover.
     */
    private function assignCoverCurrent(): self
    {
        // cover deleted
        if ($this->cover_deleted) {
            $this->core->cover = null;

            return $this;
        }

        // skip if no current cover
        if (! $this->audio->hasCover()) {
            return $this;
        }

        // skip if new cover already assigned
        if ($this->core->cover !== null) {
            return $this;
        }

        // get current cover
        $this->core->cover = new AudioCoreCover(
            data: $this->audio->getCover()->getContents(base64: true),
            mime: $this->audio->getCover()->getMimeType(),
        );

        return $this;
    }

    /**
     * Add cover to writer.
     */
    private function convertCoverToWriter(): self
    {
        if (! in_array($this->audio->getType(), self::ALLOWED_COVER_TYPE)) {
            return $this;
        }

        // skip if cover not exists
        if (! $this->core->cover) {
            return $this;
        }

        if (! $this->core->cover->data) {
            return $this;
        }

        // 'CTOC' => $old_tags['id3v2']['CTOC'],
        // 'CHAP' => $old_tags['id3v2']['CHAP'],
        // 'chapters' => $old_tags['id3v2']['chapters'],
        $this->tags['attached_picture'] = [
            [
                'data' => base64_decode($this->core->cover->data),
                'picturetypeid' => $this->core->cover->picture_type_id ?? 1,
                'description' => $this->core->cover->description ?? 'cover',
                'mime' => $this->core->cover->mime,
            ],
        ];

        return $this;
    }

    /**
     * Assign current tags.
     */
    private function assignTagsCurrent(): self
    {
        $currentTags = [];
        if (! $this->writer->remove_other_tags) {
            $currentTags = $this->audio->getRaw();
        }

        $this->tags_current = $currentTags;

        return $this;
    }

    /**
     * Assign new tags from core to array.
     */
    private function assignTagsCore(): self
    {
        $tagFormat = match ($this->audio->getType()) {
            AudioTypeEnum::id3 => AudioCore::toId3v2($this->core),
            AudioTypeEnum::vorbiscomment => AudioCore::toVorbisComment($this->core),
            AudioTypeEnum::quicktime => AudioCore::toQuicktime($this->core),
            AudioTypeEnum::matroska => AudioCore::toMatroska($this->core),
            AudioTypeEnum::ape => AudioCore::toApe($this->core),
            AudioTypeEnum::asf => AudioCore::toAsf($this->core),
            default => null,
        };

        if (! $tagFormat) {
            return $this;
        }

        $this->tags_core = $tagFormat->toArray();

        return $this;
    }

    /**
     * Assign formats to know how to write tags.
     *
     * - ID3v1 (v1 & v1.1)
     * - ID3v2 (v2.3, v2.4)
     * - APE (v2)
     * - Ogg Vorbis comments (need `vorbis-tools`)
     * - FLAC comments
     *
     * Options: `id3v1`, `id3v2.2`, `id2v2.3`, `id3v2.4`, `ape`, `vorbiscomment`, `metaflac`, `real`
     */
    private function assignFormats(): self
    {
        if (! empty($this->formats)) {
            return $this;
        }

        $this->formats = match ($this->audio->getFormat()) {
            AudioFormatEnum::flac => ['metaflac'],
            AudioFormatEnum::mp3 => ['id3v1', 'id3v2.4'],
            AudioFormatEnum::ogg => ['vorbiscomment'],
            default => [],
        };

        return $this;
    }

    /**
     * Format tags to writer format.
     *
     * @param  array<string, string>  $tags
     * @return array<string, array>
     */
    private function formatTags(array $tags): array
    {
        $items = [];
        if (! empty($tags)) {
            foreach ($tags as $key => $tag) {
                if (gettype($tag) === 'string') {
                    $items[$key] = [$tag];
                }
            }
        }

        return $items;
    }
}
