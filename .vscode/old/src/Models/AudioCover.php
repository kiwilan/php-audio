<?php

namespace Kiwilan\Audio\Models;

use Kiwilan\Audio\Id3\Reader\Id3Comments;

class AudioCover
{
    protected function __construct(
        protected ?string $contents = null,
        protected ?string $mime_type = null,
        protected ?int $width = null,
        protected ?int $height = null,
    ) {}

    public static function make(?Id3Comments $comments): ?self
    {
        if (! $comments || ! $comments->picture) {
            return null;
        }

        $self = new self;

        $self->contents = base64_encode($comments->picture->data);
        $self->mime_type = $comments->picture->image_mime;
        $self->width = $comments->picture->image_width;
        $self->height = $comments->picture->image_height;

        return $self;
    }

    /**
     * Get the contents of the cover
     *
     * By default, the contents are decoded from base64, but you can get the raw contents by passing `true` as the first argument.
     */
    public function getContents(bool $base64 = false): ?string
    {
        if (! $this->contents) {
            return null;
        }

        return $base64 ? $this->contents : base64_decode($this->contents);
    }

    /**
     * Get the MIME type of the cover
     */
    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    /**
     * Get the width of the cover
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * Get the height of the cover
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * Extract the cover to a file.
     */
    public function extractCover(string $path): void
    {
        file_put_contents($path, $this->getContents());
    }

    public function toArray(): array
    {
        return [
            'contents' => $this->contents,
            'mime_type' => $this->mime_type,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
