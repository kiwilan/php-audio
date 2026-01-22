<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3AudioQuicktime
{
    /**
     * @param  int[]|null  $stts_framecount
     * @param  array<string, array>|null  $timestamps_unix
     * @param  array<string, array>|null  $comments
     * @param  array<string, mixed>|null  $video
     * @param  array<string, mixed>|null  $audio
     * @param  Id3AudioQuicktimeChapter[]  $chapters
     */
    protected function __construct(
        protected bool $hinting = false,
        protected ?string $controller = null,
        protected ?Id3AudioQuicktimeItem $ftyp = null,
        protected ?array $timestamps_unix = null,
        protected ?int $time_scale = null,
        protected ?int $display_scale = null,
        protected ?array $video = null,
        protected ?array $audio = null,
        protected ?array $stts_framecount = null,
        protected ?array $comments = [],
        protected array $chapters = [],
        protected ?Id3AudioQuicktimeItem $free = null,
        protected ?Id3AudioQuicktimeItem $wide = null,
        protected ?Id3AudioQuicktimeItem $mdat = null,
        protected ?string $encoding = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $hinting = $metadata['hinting'] ?? false;
        $controller = $metadata['controller'] ?? null;
        $ftyp = Id3AudioQuicktimeItem::make($metadata['ftyp'] ?? null);
        $timestamps_unix = $metadata['timestamps_unix'] ?? null;
        $time_scale = $metadata['time_scale'] ?? null;
        $display_scale = $metadata['display_scale'] ?? null;
        $video = $metadata['video'] ?? null;
        $audio = $metadata['audio'] ?? null;
        $stts_framecount = $metadata['stts_framecount'] ?? null;
        $comments = $metadata['comments'] ?? [];

        $chapters = [];
        $chaps = $metadata['chapters'] ?? [];
        foreach ($chaps as $chapter) {
            $chapters[] = Id3AudioQuicktimeChapter::make($chapter);
        }

        $free = Id3AudioQuicktimeItem::make($metadata['free'] ?? null);
        $wide = Id3AudioQuicktimeItem::make($metadata['wide'] ?? null);
        $mdat = Id3AudioQuicktimeItem::make($metadata['mdat'] ?? null);
        $encoding = $metadata['encoding'] ?? null;

        $self = new self(
            hinting: $hinting,
            controller: $controller,
            ftyp: $ftyp,
            timestamps_unix: $timestamps_unix,
            time_scale: $time_scale,
            display_scale: $display_scale,
            video: $video,
            audio: $audio,
            stts_framecount: $stts_framecount,
            comments: $comments,
            chapters: $chapters,
            free: $free,
            wide: $wide,
            mdat: $mdat,
            encoding: $encoding,
        );

        return $self;
    }

    /**
     * @return Id3AudioQuicktimeChapter[]
     */
    public function getChapters(): array
    {
        return $this->chapters;
    }

    /**
     * @return array<string, array>|null
     */
    public function getComments(): ?array
    {
        return $this->comments;
    }

    /**
     * @return array<string, array>|null
     */
    public function getTimestampsUnix(): ?array
    {
        return $this->timestamps_unix;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getVideo(): ?array
    {
        return $this->video;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAudio(): ?array
    {
        return $this->audio;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getHinting(): bool
    {
        return $this->hinting;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function getFtyp(): ?Id3AudioQuicktimeItem
    {
        return $this->ftyp;
    }

    public function getTimeScale(): ?int
    {
        return $this->time_scale;
    }

    public function getDisplayScale(): ?int
    {
        return $this->display_scale;
    }

    /**
     * @return int[]|null
     */
    public function getSttsFramecount(): ?array
    {
        return $this->stts_framecount;
    }

    public function getFree(): ?Id3AudioQuicktimeItem
    {
        return $this->free;
    }

    public function getWide(): ?Id3AudioQuicktimeItem
    {
        return $this->wide;
    }

    public function getMdat(): ?Id3AudioQuicktimeItem
    {
        return $this->mdat;
    }

    public function getChapter(int $index): ?Id3AudioQuicktimeChapter
    {
        return $this->chapters[$index] ?? null;
    }

    public function toArray(): array
    {
        $chapters = [];
        foreach ($this->chapters as $chapter) {
            $chapters[] = $chapter->toArray();
        }

        return [
            'hinting' => $this->hinting,
            'controller' => $this->controller,
            'ftyp' => $this->ftyp?->toArray(),
            'timestamps_unix' => $this->timestamps_unix,
            'time_scale' => $this->time_scale,
            'display_scale' => $this->display_scale,
            'video' => $this->video,
            'audio' => $this->audio,
            'stts_framecount' => $this->stts_framecount,
            'comments' => $this->comments,
            'chapters' => $chapters,
            'free' => $this->free?->toArray(),
            'wide' => $this->wide?->toArray(),
            'mdat' => $this->mdat?->toArray(),
            'encoding' => $this->encoding,
        ];
    }
}
