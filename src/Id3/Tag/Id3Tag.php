<?php

namespace Kiwilan\Audio\Id3\Tag;

abstract class Id3Tag
{
    protected static function parseTag(array $metadata, string $key): ?string
    {
        $val = $metadata[$key] ?? null;

        if ($val === null || $val === '') {
            return null;
        }

        return $val;
    }

    public function toArray(): array
    {
        // parse all properties
        $properties = get_object_vars($this);

        // filter out null values
        $properties = array_filter($properties, fn ($value) => $value !== null);
        $properties = array_filter($properties, fn ($value) => $value !== '');

        return $properties;
    }
}
