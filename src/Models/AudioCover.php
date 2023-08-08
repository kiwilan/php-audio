<?php

namespace Kiwilan\Audio\Models;

class AudioCover
{
    protected ?string $content = null;

    protected ?string $mimeType = null;

    protected ?int $width = null;

    protected ?int $height = null;

    public static function make(?Id3Comments $comments): ?self
    {
        if (! $comments) {
            return null;
        }

        $self = new self();

        $self->content = $comments->picture()->data();
        $self->mimeType = $comments->picture()->image_mime();
        $self->width = $comments->picture()->image_width();
        $self->height = $comments->picture()->image_height();

        return $self;
    }

    public function getContent(): ?string
    {
        return $this->content;
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
