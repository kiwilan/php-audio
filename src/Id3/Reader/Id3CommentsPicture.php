<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3CommentsPicture
{
    protected function __construct(
        readonly public ?string $data = null,
        readonly public ?string $image_mime = null,
        readonly public ?int $image_width = null,
        readonly public ?int $image_height = null,
        readonly public ?string $picture_type = null,
        readonly public ?string $description = null,
        readonly public ?int $data_length = null,
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
