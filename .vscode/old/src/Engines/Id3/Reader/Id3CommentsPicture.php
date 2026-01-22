<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3CommentsPicture
{
    protected function __construct(
        public readonly ?string $data = null,
        public readonly ?string $image_mime = null,
        public readonly ?int $image_width = null,
        public readonly ?int $image_height = null,
        public readonly ?string $picture_type = null,
        public readonly ?string $description = null,
        public readonly ?int $data_length = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self(
            data: $metadata['data'] ?? null,
            image_mime: $metadata['image_mime'] ?? null,
            image_width: $metadata['image_width'] ?? null,
            image_height: $metadata['image_height'] ?? null,
            picture_type: $metadata['picturetype'] ?? null,
            description: $metadata['description'] ?? null,
            data_length: $metadata['datalength'] ?? null,
        );

        return $self;
    }
}
