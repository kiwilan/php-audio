<?php

namespace Kiwilan\Audio\Id3;

use getid3_writetags;
use Kiwilan\Audio\Audio;
use Kiwilan\Audio\Core\AudioCore;
use Kiwilan\Audio\Enums\AudioFormatEnum;
use Kiwilan\Audio\Enums\AudioTypeEnum;

class Id3Writer
{
    /**
     * @param  array<string, string>  $options
     * @param  array<string, array>  $new_tags
     * @param  string[]  $warnings
     * @param  string[]  $errors
     * @param  string[]  $tag_formats
     */
    protected function __construct(
        protected Audio $audio,
        protected getid3_writetags $writer,
        protected AudioCore $core,
        // protected array $options = ['encoding' => 'UTF-8'],
        protected array $new_tags = [],
        // protected array $warnings = [],
        // protected array $errors = [],
        // protected bool $override_tags = true,
        protected bool $remove_old_tags = false,
        // protected bool $fail_on_error = true,
        protected array $tag_formats = [],
        // protected ?string $path = null,
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

    /**
     * Allow to remove other tags when writing tags.
     */
    public function removeOtherTags(): self
    {
        $this->writer->remove_other_tags = true;

        return $this;
    }

    public function title(string $title): self
    {
        $this->core->title = $title;

        return $this;
    }

    public function album(string $album): self
    {
        $this->core->album = $album;

        return $this;
    }

    public function save(): void
    {
        $this->assignTags();

        $this->writer->tagformats = $this->tag_formats;
        $this->writer->tag_data = $this->new_tags;

        $this->success = $this->writer->WriteTags();
        ray($this);
    }

    /**
     * Assign tags from core to tag formats.
     */
    private function assignTags(): self
    {
        $this->parseTagFormats();

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

        $this->new_tags = [
            ...$this->audio->getRawTags(), // old tags
            ...$convert->toArray(), // new tags
        ];
        $this->new_tags = $this->convertTags($this->new_tags);

        // $tags = [];
        // if ($convert) {
        //     $tags = $convert->toArray();
        // }

        // $tags = $this->convertTags($tags);
        // $this->attachCover($tags);

        // $this->tags = [
        //     ...$this->tags,
        //     ...$tags,
        // ];

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
}
