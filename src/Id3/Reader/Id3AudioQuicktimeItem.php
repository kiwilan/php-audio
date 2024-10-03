<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3AudioQuicktimeItem
{
    protected function __construct(
        protected ?string $hierarchy = null,
        protected ?string $name = null,
        protected ?int $size = null,
        protected ?int $offset = null,
        protected ?string $signature = null,
        protected ?int $unknown_1 = null,
        protected ?string $fourcc = null,
    ) {}

    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $hierarchy = $metadata['hierarchy'] ?? null;
        $name = $metadata['name'] ?? null;
        $size = $metadata['size'] ?? null;
        $offset = $metadata['offset'] ?? null;
        $signature = $metadata['signature'] ?? null;
        $unknown_1 = $metadata['unknown_1'] ?? null;
        $fourcc = $metadata['fourcc'] ?? null;

        $self = new self(
            hierarchy: $hierarchy,
            name: $name,
            size: $size,
            offset: $offset,
            signature: $signature,
            unknown_1: $unknown_1,
            fourcc: $fourcc,
        );

        return $self;
    }

    public function getHierarchy(): ?string
    {
        return $this->hierarchy;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function getUnknown1(): ?int
    {
        return $this->unknown_1;
    }

    public function getFourcc(): ?string
    {
        return $this->fourcc;
    }

    public function toArray(): array
    {
        return [
            'hierarchy' => $this->hierarchy,
            'name' => $this->name,
            'size' => $this->size,
            'offset' => $this->offset,
            'signature' => $this->signature,
            'unknown_1' => $this->unknown_1,
            'fourcc' => $this->fourcc,
        ];
    }
}
