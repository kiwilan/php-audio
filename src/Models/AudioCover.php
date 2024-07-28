<?php

namespace Kiwilan\Audio\Models;

class AudioCover
{
    protected ?string $contents = null;

    protected ?string $mimeType = null;

    protected ?int $width = null;

    protected ?int $height = null;

    public static function make(?Id3Comments $comments): ?self
    {
        if (! $comments) {
            return null;
        }

        $self = new self;

        $self->contents = $comments->picture()->data();
        $self->mimeType = $comments->picture()->image_mime();
        $self->width = $comments->picture()->image_width();
        $self->height = $comments->picture()->image_height();

        return $self;
    }

    /**
     * @deprecated Use `getContents()` instead.
     */
    public function getContent(): ?string
    {
        return $this->contents;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }
}
