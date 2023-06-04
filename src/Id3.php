<?php

namespace Kiwilan\Audio;

use getID3;
use Kiwilan\Audio\Models\Id3Item;

class Id3
{
    protected array $raw = [];

    protected ?Id3Item $item = null;

    protected function __construct(
        protected getID3 $instance,
    ) {
    }

    public static function make(string $path): self
    {
        $self = new self(instance: new getID3());
        $self->raw = $self->instance->analyze($path);
        $self->item = Id3Item::make($self->raw);

        return $self;
    }

    public function raw(): array
    {
        return $this->raw;
    }

    public function item(): ?Id3Item
    {
        return $this->item;
    }

    public function instance(): getID3
    {
        return $this->instance;
    }
}
