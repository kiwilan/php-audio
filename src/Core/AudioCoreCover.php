<?php

namespace Kiwilan\Audio\Core;

class AudioCoreCover
{
    public function __construct(
        protected ?string $data = null,
        protected ?int $picture_type_id = null,
        protected ?string $description = null,
        protected ?string $mime = null,
    ) {}

    public static function make(string $pathOrData): self
    {
        $self = new self;

        if (file_exists($pathOrData)) {
            $image = getimagesize($pathOrData);
            $self->data = base64_encode(file_get_contents($pathOrData));
            $self->picture_type_id = $image[2];
            $self->description = 'cover';
            $self->mime = $image['mime'];

            return $self;
        }

        $image = getimagesizefromstring($pathOrData);
        $self->data = base64_encode($pathOrData);
        $self->picture_type_id = $image[2];
        $self->mime = $image['mime'];
        $self->description = 'cover';

        return $self;
    }

    public function data(): ?string
    {
        return $this->data;
    }

    public function pictureTypeId(): ?int
    {
        return $this->picture_type_id;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function mime(): ?string
    {
        return $this->mime;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'picture_type_id' => $this->picture_type_id,
            'description' => $this->description,
            'mime' => $this->mime,
        ];
    }
}
