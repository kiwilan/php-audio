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
    /**
     * @param  array<string, array>  $new_tags
     * @param  string[]  $custom_tags
     * @param  string[]  $warnings
     * @param  string[]  $errors
     * @param  string[]  $tag_formats
     */
    protected function __construct(
        protected Audio $audio,
        protected getid3_writetags $writer,
        protected AudioCore $core,
        protected bool $is_manual = false,
        protected array $new_tags = [],
        protected array $custom_tags = [],
        protected array $warnings = [],
        protected array $errors = [],
        protected bool $remove_old_tags = false,
        protected ?array $cover = null,
        protected bool $has_new_cover = false,
        protected bool $delete_cover = false,
        protected bool $skip_errors = true,
        protected array $tag_formats = [],
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

    public function albumArtist(?string $album_artist): self
    {
        $this->core->album_artist = $album_artist;

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
     * Add custom tags without dedicated method.
     *
     * Example:
     *
     * ```php
     * $audio->update()->tag('series-part', '1');
     * ```
     */
    public function tag(string $key, string|int|bool|null $value): self
    {
        $this->custom_tags[$key] = $value;

        return $this;
    }

    /**
     * To update path to save file.
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
     * Advanced usage only to save manually tags.
     *
     * @param  string[]  $tag_formats
     */
    public function tagFormats(array $tag_formats): self
    {
        $this->tag_formats = $tag_formats;

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
        $this->has_new_cover = true;

        return $this;
    }

    /**
     * Remove cover from tags.
     */
    public function removeCover(): self
    {
        $this->delete_cover = true;

        return $this;
    }

    /**
     * Set manually tags, to know which key used for which tag, you have to refer to documentation.
     *
     * WARNING: This method is for advanced usage only, if you use it, this will override all other tags.
     *
     * @docs https://github.com/kiwilan/php-audio#convert-properties
     *
     * For example, album artist for `id3` encoded files, is `band` key.
     *
     * @param  array<string, string>  $tags
     */
    public function tags(array $tags): self
    {
        $this->new_tags = $this->convertTags($tags);
        $this->is_manual = true;

        return $this;
    }

    /**
     * Handle errors when writing tags.
     */
    public function handleErrors(): self
    {
        $this->skip_errors = false;

        return $this;
    }

    public function save(): bool
    {
        $this->attachCover();
        $this->parseTagFormats();
        if (! $this->is_manual) {
            $this->assignTags();
        }

        $this->writer->tagformats = $this->tag_formats;
        $this->writer->tag_data = $this->new_tags;

        $this->success = $this->writer->WriteTags();

        $this->errors = $this->writer->errors;
        $this->warnings = $this->writer->warnings;

        $this->parseErrors();

        return $this->success;
    }

    private function parseErrors(): void
    {
        $this->errors = $this->writer->errors;
        $this->warnings = $this->writer->warnings;

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
     * Assign tags from core to tag formats.
     */
    private function assignTags(): self
    {
        $convert = match ($this->audio->getType()) {
            AudioTypeEnum::id3 => AudioCore::toId3v2($this->core),
            AudioTypeEnum::vorbiscomment => AudioCore::toVorbisComment($this->core),
            AudioTypeEnum::quicktime => AudioCore::toQuicktime($this->core),
            AudioTypeEnum::matroska => AudioCore::toMatroska($this->core),
            AudioTypeEnum::ape => AudioCore::toApe($this->core),
            AudioTypeEnum::asf => AudioCore::toAsf($this->core),
            default => null,
        };

        if (! $convert) {
            return $this;
        }

        $oldTags = [];
        if (! $this->writer->remove_other_tags) {
            $oldTags = $this->audio->getRaw();
        }

        $this->new_tags = [
            ...$oldTags, // old tags
            ...$convert->toArray(), // new tags
        ];
        $this->new_tags = $this->convertTags($this->new_tags);

        if ($this->cover && ! $this->delete_cover) {
            $this->new_tags['attached_picture'][0] = $this->cover;
        }

        return $this;
    }

    /**
     * Assign tag formats to know how to write tags.
     *
     * - ID3v1 (v1 & v1.1)
     * - ID3v2 (v2.3, v2.4)
     * - APE (v2)
     * - Ogg Vorbis comments (need `vorbis-tools`)
     * - FLAC comments
     *
     * Options: `id3v1`, `id3v2.2`, `id2v2.3`, `id3v2.4`, `ape`, `vorbiscomment`, `metaflac`, `real`
     */
    private function parseTagFormats(): self
    {
        if (! empty($this->tagFormats)) {
            return $this;
        }

        $this->tag_formats = match ($this->audio->getFormat()) {
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
            default => [],
        };

        return $this;
    }

    private function attachCover(): void
    {
        if ($this->audio->hasCover() && ! $this->has_new_cover && ! $this->delete_cover) {
            $this->core->cover = new AudioCoreCover(
                data: $this->audio->getCover()->getContents(base64: true),
                mime: $this->audio->getCover()->getMimeType(),
            );
        }

        $coverFormatsAllowed = [AudioFormatEnum::mp3];
        if ($this->core->cover && in_array($this->audio->getFormat(), $coverFormatsAllowed)) {
            // $tags = [
            //     ...$tags,
            //     'CTOC' => $old_tags['id3v2']['CTOC'],
            //     'CHAP' => $old_tags['id3v2']['CHAP'],
            //     'chapters' => $old_tags['id3v2']['chapters'],
            // ];
            $this->cover = [
                'data' => base64_decode($this->core->cover->data),
                'picturetypeid' => $this->core->cover->picture_type_id ?? 1,
                'description' => $this->core->cover->description ?? 'cover',
                'mime' => $this->core->cover->mime,
            ];
            $this->core->has_cover = true;
        }

        if ($this->delete_cover) {
            $this->cover = null;
            $this->core->has_cover = false;
        }
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
                if (gettype($tag) === 'string') {
                    $items[$key] = [$tag];
                }
            }
        }

        if ($attached) {
            $items['attached_picture'] = $attached;
        }

        return $items;
    }
}
