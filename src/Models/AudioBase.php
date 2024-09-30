<?php

namespace Kiwilan\Audio\Models;

interface AudioBase
{
    /**
     * Convert to array
     */
    public function toArray(): array;

    /**
     * Convert to JSON
     */
    public function toJson(): string;

    /**
     * Convert to string
     */
    public function __toString(): string;
}
