<?php

namespace Kiwilan\Audio\Core;

class AudioCoreCover
{
    public function __construct(
        public ?string $data = null,
        public ?int $picture_type_id = null,
        public ?string $description = null,
        public ?string $mime = null,
    ) {}

    public static function make(string $pathOrData): self
    {
        $self = new self;

        $image = file_exists($pathOrData)
            ? getimagesize($pathOrData)
            : getimagesizefromstring($pathOrData);
        $self->data = file_exists($pathOrData)
            ? base64_encode(file_get_contents($pathOrData))
            : base64_encode($pathOrData);
        $self->picture_type_id = $image[2];
        $self->description = 'cover';
        $self->mime = $image['mime'];

        return $self;
    }
}
