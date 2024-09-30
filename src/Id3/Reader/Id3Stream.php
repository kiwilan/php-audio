<?php

namespace Kiwilan\Audio\Id3\Reader;

class Id3Stream extends Id3AudioBase
{
    public static function make(?array $metadata): ?self
    {
        if (! $metadata) {
            return null;
        }

        $self = new self($metadata);

        return $self;
    }
}
