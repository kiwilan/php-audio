<?php

namespace Kiwilan\Audio\Id3;

use getID3;

class Id3Reader
{
    protected function __construct(
        protected getID3 $instance,
        protected bool $is_writable = false,
        protected array $raw = [],
    ) {}

    public static function make(string $path): self
    {
        $self = new self(new getID3);

        $metadata = $self->instance->analyze($path);
        $is_writable = $self->instance->is_writable($path);

        $metadata['id3v2']['APIC'] = null;
        $metadata['ape']['items']['cover art (front)'] = null;
        $metadata['comments'] = null;

        return $self;
    }
}
